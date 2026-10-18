<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;

class EmailCopyTest extends TestCase
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

    public function test_all_indonesian_email_copy_is_concise_and_uses_a_consistent_voice(): void
    {
        app()->setLocale('id');
        foreach (EmailPresentationTest::templates() as [$view, $locale]) {
            if ($locale !== 'id') continue;
            $html = view($view, EmailPresentationTest::fixture())->render();
            $document = new \DOMDocument;
            @$document->loadHTML('<?xml encoding="UTF-8">'.$html);
            $text = $document->getElementsByTagName('body')->item(0)->textContent;
            $this->assertDoesNotMatchRegularExpression('/\b(?:Anda|anda|Yth|cadence|ekosistem)\b/u', $text, $view);
            $this->assertLessThanOrEqual(220, count(preg_split('/\s+/u', trim($text))), $view);
            $this->assertStringNotContainsString('emails.', $text, $view);
        }
    }

    public function test_copy_catalogues_match_and_keep_dynamic_placeholders(): void
    {
        $flatten = function (array $catalogue, string $prefix = '') use (&$flatten): array {
            $result = [];
            foreach ($catalogue as $key => $value) {
                $path = $prefix.$key;
                if (is_array($value)) $result += $flatten($value, $path.'.');
                else $result[$path] = $value;
            }
            return $result;
        };
        $en = $flatten(require lang_path('en/emails.php'));
        $id = $flatten(require lang_path('id/emails.php'));
        $this->assertSame(array_keys($en), array_keys($id));
        foreach ($en as $key => $value) {
            $this->assertNotSame('', trim($value), $key);
            $this->assertNotSame('', trim($id[$key]), $key);
            preg_match_all('/:[A-Za-z][A-Za-z0-9_]*/', $value, $first);
            preg_match_all('/:[A-Za-z][A-Za-z0-9_]*/', $id[$key], $second);
            sort($first[0]); sort($second[0]);
            $this->assertSame($first[0], $second[0], $key);
        }
    }

    public function test_credit_payment_receipt_does_not_promise_premium_or_invent_a_discount(): void
    {
        app()->setLocale('id');
        $data = EmailPresentationTest::fixture();
        $data['payment']->forceFill(['amount' => 55000, 'notes' => 'photo_credits']);
        $html = view('emails.payment-success', $data)->render();
        $this->assertStringContainsString('55.000', $html);
        $this->assertStringContainsString($data['payment']->order_id, $html);
        $this->assertStringNotContainsString('Premium kamu sudah aktif', $html);
        $this->assertStringNotContainsString('Seumur Hidup', $html);
        $this->assertStringNotContainsString('- Rp', $html);
    }

    public function test_security_instructions_and_real_membership_expiry_remain_visible(): void
    {
        app()->setLocale('id');
        $data = EmailPresentationTest::fixture();
        $data['user']->forceFill(['premium_until' => '2026-11-21 23:59:59']);
        $otp = view('emails.otp_verification', $data)->render();
        $this->assertStringContainsString('123456', $otp);
        $this->assertStringContainsString('10 menit', $otp);
        $this->assertStringContainsString('Jangan bagikan', $otp);
        $premium = view('emails.premium.granted', $data)->render();
        $this->assertStringContainsString('21 Nov 2026', $premium);
        $this->assertStringNotContainsString('Lifetime', $premium);
    }

    public function test_every_email_subject_uses_the_updated_copy_in_both_languages(): void
    {
        \Illuminate\Support\Facades\Http::preventStrayRequests();
        foreach (['en', 'id'] as $locale) {
            app()->setLocale($locale);
            $data = EmailPresentationTest::fixture() + ['customSubject' => 'A short workspace update'];
            foreach (glob(app_path('Mail/*.php')) as $file) {
                $class = 'App\\Mail\\'.basename($file, '.php');
                $reflection = new \ReflectionClass($class);
                $arguments = array_map(fn ($parameter) => $data[$parameter->getName()], $reflection->getConstructor()->getParameters());
                $mail = $reflection->newInstanceArgs($arguments);
                $mail->render();
                $this->assertNotEmpty($mail->subject, $class);
                $this->assertStringNotContainsString('emails.', $mail->subject, $class);
                $this->assertDoesNotMatchRegularExpression('/\b(?:Anda|anda|GRATIS)\b|[\x{1F000}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $mail->subject, $class);
            }
        }
    }

    public function test_missing_dates_show_a_clear_fallback_instead_of_a_timezone_only(): void
    {
        app()->setLocale('id');
        $data = EmailPresentationTest::fixture();
        $data['user']->forceFill(['created_at' => null, 'email_verified_at' => null]);
        $data['jobApplication']->forceFill(['interview_date' => null]);
        $data['ticket']->forceFill(['created_at' => null]);
        foreach (['interview_scheduled', 'interview_reminder', 'new-user-registration', 'user-email-verified', 'support-ticket-notification'] as $view) {
            $html = view('emails.'.$view, $data)->render();
            $this->assertStringContainsString('Belum dicatat', $html, $view);
            $this->assertDoesNotMatchRegularExpression('/>\s*WIB\s*</', $html, $view);
        }
    }
}
