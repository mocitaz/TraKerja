<?php

namespace Tests\Feature;

use App\Models\JobApplication;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\UserGoal;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EmailPresentationTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();
        config(['app.key' => str_repeat('t', 32), 'app.url' => 'https://example.test']);
        return $app;
    }

    public static function templates(): array
    {
        $cases = [];
        foreach (array_merge(glob(dirname(__DIR__, 2).'/resources/views/emails/*.blade.php'), glob(dirname(__DIR__, 2).'/resources/views/emails/premium/*.blade.php')) as $file) {
            $view = 'emails.'.str_replace(['/', '.blade.php'], ['.', ''], substr($file, strlen(dirname(__DIR__, 2).'/resources/views/emails/')));
            foreach (['en', 'id'] as $locale) $cases[$view.'-'.$locale] = [$view, $locale];
        }
        return $cases;
    }

    public static function fixture(): array
    {
        $user = new User;
        $user->forceFill(['id' => 42, 'name' => 'Alexandra & Partners', 'email' => 'alexandra@example.test', 'created_at' => now(), 'email_verified_at' => now()]);
        $jobApplication = new JobApplication(['company_name' => 'Example & Co', 'position' => 'Product Designer', 'recruitment_stage' => 'Assessment Test', 'interview_type' => 'Online', 'interview_platform' => 'Zoom', 'interview_date' => now()->addDay(), 'interview_location' => 'https://example.test/meeting', 'interview_notes' => 'Bring your portfolio.']);
        $jobApplication->setRelation('user', $user);
        $payment = new Payment;
        $payment->forceFill(['order_id' => 'SYNTHETIC-ORDER-1234567890', 'amount' => 35000, 'payment_channel_name' => 'QRIS', 'paid_at' => now()])->setRelation('user', $user);
        $goal = new UserGoal;
        $goal->forceFill(['title' => 'First applications', 'description' => 'A synthetic target', 'target_date' => now()->addWeek()])->setRelation('user', $user);
        $ticket = new SupportTicket;
        $ticket->forceFill(['subject' => 'Synthetic request', 'category' => 'technical_issue', 'message' => 'A synthetic support message', 'created_at' => now()])->setRelation('user', $user);
        return compact('user', 'jobApplication', 'payment', 'goal', 'ticket') + [
            'actionUrl' => 'https://example.test/action?signature=synthetic&expires=123',
            'subject' => 'A short workspace update', 'emailContent' => 'Keep moving forward.',
            'buttonText' => 'Open workspace', 'buttonUrl' => 'https://example.test/dashboard',
            'otp' => '123456', 'monthName' => 'October', 'year' => 2026,
        ];
    }

    #[DataProvider('templates')]
    public function test_email_documents_render_in_each_language_without_client_scripts(string $view, string $locale): void
    {
        Mail::fake();
        Http::preventStrayRequests();
        app()->setLocale($locale);
        $html = view($view, self::fixture())->render();
        $document = new \DOMDocument;
        @$document->loadHTML('<?xml encoding="UTF-8">'.$html);
        $this->assertSame($locale, $document->documentElement->getAttribute('lang'));
        $this->assertSame(1, $document->getElementsByTagName('h1')->length);
        $this->assertStringNotContainsString('onerror=', $html);
        $this->assertSame(0, $document->getElementsByTagName('script')->length);
        $this->assertStringContainsString('https://example.test/profile?tab=notifications', $html);
        Mail::assertNothingSent();
    }

    public function test_authentication_links_and_custom_text_are_preserved_and_escaped(): void
    {
        $data = self::fixture();
        foreach (['email-verification', 'password-reset'] as $template) {
            $html = view('emails.'.$template, $data)->render();
            $this->assertSame(2, substr_count($html, 'href="'.e($data['actionUrl']).'"'));
        }
        $data['emailContent'] = '<script>alert("test")</script>';
        $html = view('emails.custom-email-blast', $data)->render();
        $this->assertStringContainsString(e($data['emailContent']), $html);
        $this->assertStringNotContainsString('<script>', $html);
        app()->setLocale('id');
        $data['subject'] = 'Help';
        $html = view('emails.custom-email-blast', $data)->render();
        $this->assertMatchesRegularExpression('/<h1[^>]*>Help<\/h1>/', $html);
    }

    public function test_markdown_notifications_share_the_document_layout_and_action(): void
    {
        $html = (string) app(\Illuminate\Mail\Markdown::class)->render('notifications::email', [
            'greeting' => 'Your workspace update', 'level' => 'info',
            'introLines' => ['A synthetic notification.'], 'outroLines' => [],
            'actionText' => 'Open workspace', 'actionUrl' => 'https://example.test/dashboard',
            'displayableActionUrl' => 'example.test/dashboard', 'salutation' => null,
        ]);
        $document = new \DOMDocument;
        @$document->loadHTML($html);
        $this->assertSame(1, $document->getElementsByTagName('html')->length);
        $this->assertStringContainsString('Your workspace update', $html);
        $this->assertStringContainsString('https://example.test/dashboard', $html);
        $this->assertStringContainsString('https://example.test/profile?tab=notifications', $html);
        $this->assertStringNotContainsString('onerror=', $html);
        $this->assertSame(1, (new \DOMXPath($document))->query('//table[@class="subcopy"]')->length);
    }

    public function test_actual_mailables_and_auth_notifications_use_the_shared_email_document(): void
    {
        Mail::fake();
        Http::preventStrayRequests();
        foreach (['en', 'id'] as $locale) {
            app()->setLocale($locale);
            $data = self::fixture() + ['customSubject' => 'A short workspace update'];
            foreach (glob(app_path('Mail/*.php')) as $file) {
                $class = 'App\\Mail\\'.basename($file, '.php');
                $reflection = new \ReflectionClass($class);
                $arguments = array_map(fn ($parameter) => $data[$parameter->getName()], $reflection->getConstructor()->getParameters());
                $html = $reflection->newInstanceArgs($arguments)->render();
                $document = new \DOMDocument;
                @$document->loadHTML($html);
                $this->assertSame(1, $document->getElementsByTagName('html')->length, $class);
                $this->assertSame($locale, $document->documentElement->getAttribute('lang'), $class);
                $this->assertStringContainsString('/profile?tab=notifications', $html, $class);
            }
            foreach ([new \App\Notifications\CustomVerifyEmail, new \App\Notifications\CustomResetPassword('synthetic-token')] as $notification) {
                $html = (string) $notification->toMail($data['user'])->render();
                $this->assertStringContainsString('class="email-frame"', $html);
            }
        }
        Mail::assertNothingSent();
    }
}
