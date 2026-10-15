<?php

namespace Tests\Feature;

use App\Http\Controllers\CvBuilderController;
use App\Models\User;
use App\Models\UserProfile;
use App\Support\CvDocument;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CvTemplateRenderingTest extends TestCase
{
    public function createApplication()
    {
        $app = require dirname(__DIR__, 2).'/bootstrap/app.php';
        $app->useEnvironmentPath('/dev/null');
        $this->traitsUsedByTest = array_flip(class_uses_recursive(static::class));
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }

    public static function templates(): array
    {
        $cases = [];
        foreach (['minimal', 'professional', 'creative', 'elegant'] as $template) {
            foreach (['short', 'long', 'empty', 'compact', 'spacious', 'sparse', 'long-name'] as $length) {
                $cases["$template-$length"] = [$template, $length];
            }
        }
        return $cases;
    }

    public static function fixture(string $length): array
    {
        $user = new User(['name' => 'Alexandra Prameswari', 'email' => 'alexandra@example.test']);
        $user->setRelation('profile', new UserProfile([
            'phone_number' => '+62 812 0000 0000', 'domicile' => 'Jakarta, Indonesia',
            'linkedin_url' => 'https://linkedin.com/in/sample-profile',
            'portfolio_url' => 'https://portfolio.example.test/work',
            'bio' => $length === 'empty' ? null : 'Product and operations leader connecting customer needs, delivery teams, and measurable business outcomes. Experienced in building reliable processes across distributed teams.',
        ]));
        if ($length === 'long-name') {
            $user->name = 'Alexandra Prameswari Natalia Wijaya Kusumawardhani';
            $user->email = str_repeat('alexandra', 8).'@example.test';
            $user->profile->domicile = 'Jakarta, Indonesia / Available for international remote collaboration';
        }
        $description = "- Led a cross-functional team to simplify customer onboarding and improve delivery reliability.\n- Built **operational reporting** that helped teams identify bottlenecks and prioritize improvements.\n- Partnered with engineering and design to translate research into clear product requirements.";
        $experiences = collect();
        if ($length !== 'empty') {
            for ($i = 0; $i < ($length === 'long' ? 9 : 1); $i++) {
                $experiences->push((object) ['position' => $i === 0 ? 'Senior Product Manager' : 'Product Operations Manager', 'company_name' => 'Example Technology Group '.($i + 1), 'location' => 'Jakarta / Remote', 'start_date' => '2020-01-01', 'end_date' => '2023-12-01', 'is_current' => $i === 0, 'description' => $length === 'long' && $i === 0 ? str_repeat($description."\n", 14)."\nLONG_ENTRY_END" : $description]);
            }
        }
        if ($length === 'long-name') {
            $experiences->first()->position .= ' for International Product Operations and Business Transformation';
            $experiences->first()->location = 'Jakarta, Singapore, and remote teams across Asia Pacific';
        }
        $date = Carbon::parse('2024-06-01');
        $educations = $length === 'empty' ? collect() : collect([(object) ['institution_name' => 'Example Institute of Technology', 'degree' => 'Bachelor of Business', 'major' => 'Information Systems', 'location' => 'Bandung', 'start_date' => '2015-08-01', 'end_date' => '2019-07-01', 'is_current' => false, 'description' => 'Graduated with a focus on product analytics and service design.', 'gpa' => '3.78']]);
        $projects = $length === 'empty' ? collect() : collect([(object) ['project_name' => 'Customer Experience Platform', 'role' => 'Product Lead', 'start_date' => null, 'end_date' => null, 'is_ongoing' => false, 'description' => 'Designed a shared workflow connecting support insights to the product roadmap.', 'technologies' => ['SQL', 'Figma', 'Analytics'], 'project_url' => 'https://example.test/projects/'.str_repeat('long-path-', 15), 'repository_url' => 'https://example.test/repository']]);
        $organizations = $length === 'empty' ? collect() : collect([(object) ['position' => 'Program Coordinator', 'organization_name' => 'Community Design Network', 'start_date' => '2021-01-01', 'end_date' => '2022-01-01', 'is_current' => false, 'description' => 'Coordinated mentoring sessions for early-career practitioners.']]);
        $achievements = $length === 'empty' ? collect() : collect([(object) ['title' => 'Product Strategy Certification', 'issuer' => 'Example Learning Institute', 'date' => $date, 'description' => null, 'certificate_url' => 'https://example.test/certificate']]);
        $skills = $length === 'empty' ? collect() : collect([(object) ['category' => 'Product', 'skill_name' => 'Product strategy'], (object) ['category' => 'Product', 'skill_name' => 'Research & prioritization'], (object) ['category' => 'Tools', 'skill_name' => 'SQL, Figma, Analytics']]);
        if ($length === 'sparse') { $educations = $projects = $organizations = $achievements = collect(); }
        return compact('user', 'experiences', 'educations', 'projects', 'organizations', 'achievements', 'skills');
    }

    #[DataProvider('templates')]
    public function test_real_templates_render_a4_with_all_content(string $template, string $length): void
    {
        config(['logging.default' => 'null']);
        $data = self::fixture($length);
        $request = Request::create('/cv/preview', 'GET', ['margin' => $length === 'compact' ? '10mm' : ($length === 'spacious' ? '20mm' : '15mm'), 'font_size' => $length === 'compact' ? '8.5pt' : ($length === 'spacious' ? '10.5pt' : '9.5pt')]);
        $method = new \ReflectionMethod(CvBuilderController::class, 'renderDocument');
        $pdf = $method->invoke(app(CvBuilderController::class), $request, $template, $data);
        $bytes = $pdf->output();
        $this->assertStringStartsWith('%PDF-', $bytes);
        $this->assertFalse($pdf->getDomPDF()->getOptions()->isRemoteEnabled());
        $canvas = $pdf->getDomPDF()->getCanvas();
        $this->assertEqualsWithDelta(595.28, $canvas->get_width(), 1);
        $this->assertEqualsWithDelta(841.89, $canvas->get_height(), 1);
        $this->assertGreaterThanOrEqual($length === 'long' ? 3 : 1, $canvas->get_page_count());
        if (in_array($length, ['empty', 'sparse'])) $this->assertSame(1, $canvas->get_page_count());
        if ($template === 'minimal' && in_array($length, ['short', 'compact'])) $this->assertSame(1, $canvas->get_page_count());
        $html = view("cv-templates.$template", $data)->render();
        $this->assertStringNotContainsString('issue_date', $html);
        if (in_array($template, ['minimal', 'professional', 'elegant'])) $this->assertStringNotContainsString('<table', $html);
        if (!in_array($length, ['empty', 'sparse'])) {
            $this->assertStringContainsString('Jun 2024', $html);
            $this->assertStringContainsString('GPA: 3.78', $html);
            $this->assertStringContainsString('Repository:', $html);
        }
        if ($dir = getenv('CV_REVIEW_DIR')) {
            if (!is_dir($dir)) mkdir($dir, 0700, true);
            file_put_contents("$dir/$template-$length.pdf", $bytes);
        }
    }

    public static function photoShapes(): array
    {
        return ['square' => [600, 600, 'short'], 'landscape-long-name' => [900, 450, 'long-name']];
    }

    #[DataProvider('photoShapes')]
    public function test_creative_embeds_uploaded_profile_photo_and_prioritizes_projects(int $width, int $height, string $length): void
    {
        if (!extension_loaded('gd')) $this->markTestSkipped('GD is required for the portrait fixture.');
        \Illuminate\Support\Facades\Storage::fake('public');
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocate($image, 239, 224, 204));
        imagefilledrectangle($image, 0, (int)($height / 2), $width, $height, imagecolorallocate($image, 50, 68, 85));
        imagefilledellipse($image, (int)($width / 2), (int)($height / 3), (int)($height / 3), (int)($height / 3), imagecolorallocate($image, 176, 111, 74));
        ob_start(); imagepng($image); $bytes = ob_get_clean(); imagedestroy($image);
        \Illuminate\Support\Facades\Storage::disk('public')->put('logos/cv-portrait-fixture.png', $bytes);
        $data = self::fixture($length);
        $data['user']->logo = 'logos/cv-portrait-fixture.png';
        $photo = CvDocument::profilePhoto($data['user']->logo);
        $this->assertStringStartsWith('data:image/jpeg;base64,', $photo);
        $size = getimagesizefromstring(base64_decode(explode(',', $photo, 2)[1]));
        $this->assertSame([500, 600], array_slice($size, 0, 2));
        $html = view('cv-templates.creative', $data)->render();
        $this->assertStringContainsString('<img class="creative-portrait"', $html);
        $this->assertStringNotContainsString('<div class="creative-monogram">', $html);
        $this->assertLessThan(strpos($html, 'Work Experience</h2>'), strpos($html, 'Projects</h2>'));
        $dom = new \DOMDocument(); @$dom->loadHTML($html);
        $headings = (new \DOMXPath($dom))->query('//h2/span[@class="section-number"]');
        $this->assertSame(range(1, 7), array_map(fn($node) => (int)$node->textContent, iterator_to_array($headings)));
        $method = new \ReflectionMethod(CvBuilderController::class, 'renderDocument');
        $pdf = $method->invoke(app(CvBuilderController::class), Request::create('/cv/preview'), 'creative', $data);
        $bytes = $pdf->output();
        $this->assertStringStartsWith('%PDF-', $bytes);
        $this->assertStringContainsString('/Subtype /Image', $bytes);
        if ($dir = getenv('CV_REVIEW_DIR')) {
            if (!is_dir($dir)) mkdir($dir, 0700, true);
            file_put_contents("$dir/creative-photo-$length.pdf", $bytes);
        }
    }

    public function test_profile_photo_rejects_remote_missing_and_invalid_files(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        \Illuminate\Support\Facades\Storage::disk('public')->put('logos/not-an-image.txt', 'Synthetic fixture');
        $this->assertNull(CvDocument::profilePhoto(null));
        $this->assertNull(CvDocument::profilePhoto('logos/missing.png'));
        $this->assertNull(CvDocument::profilePhoto('logos/not-an-image.txt'));
        $this->assertNull(CvDocument::profilePhoto('https://example.test/photo.png'));
        $this->assertNull(CvDocument::profilePhoto('../../outside.png'));
    }

    public function test_inline_preview_uses_export_renderer_and_preserves_quota(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:', 'cache.default' => 'array', 'logging.default' => 'null', 'app.key' => str_repeat('t', 32), 'session.driver' => 'array']);
        \Illuminate\Support\Facades\Cache::put('setting_monetization_enabled', false);
        foreach (['user_profiles', 'user_experiences', 'user_educations', 'user_projects', 'user_organizations', 'user_achievements', 'user_skills'] as $table) {
            \Illuminate\Support\Facades\Schema::create($table, function (\Illuminate\Database\Schema\Blueprint $t) { $t->id(); $t->integer('user_id'); $t->integer('display_order')->default(0); });
        }
        $user = new User(['name' => 'Preview Test', 'email' => 'preview@example.test']);
        $user->id = 42;
        $user->cv_generated_this_month = 2;
        \Illuminate\Support\Facades\Auth::setUser($user);
        $response = app(CvBuilderController::class)->preview(Request::create('/cv/preview', 'GET', ['canvas' => 1, 'template' => 'professional', 'margin' => '20mm', 'font_size' => '10.5pt']));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline;', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertSame(2, $user->cv_generated_this_month);
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }

    public function test_production_pdf_policy_allows_only_same_origin_viewer_and_keeps_html_policy(): void
    {
        config(['app.key' => str_repeat('t', 32), 'session.driver' => 'array']);
        app()->detectEnvironment(fn () => 'production');
        try {
            $request = Request::create('/cv/preview?canvas=1', 'GET');
            $route = new \Illuminate\Routing\Route('GET', '/cv/preview', fn () => null);
            $route->name('cv-builder.preview.get');
            $request->setRouteResolver(fn () => $route);
            $middleware = new \App\Http\Middleware\SecurityHeaders();
            $pdf = $middleware->handle($request, fn () => response('%PDF-fixture')->header('Content-Type', 'application/pdf'));
            $this->assertStringContainsString("object-src 'self';", $pdf->headers->get('Content-Security-Policy'));
            $this->assertSame('SAMEORIGIN', $pdf->headers->get('X-Frame-Options'));
            $html = $middleware->handle($request, fn () => response('<html></html>'));
            $this->assertStringContainsString("object-src 'none';", $html->headers->get('Content-Security-Policy'));
        } finally {
            app()->detectEnvironment(fn () => 'testing');
        }
    }

    public function test_description_is_safe_valid_html_and_long_tokens_can_wrap(): void
    {
        $html = CvDocument::description("- First\n\n**Heading**\n- <script>alert(1)</script>\nEnd");
        $this->assertSame(2, substr_count($html, '<ul>'));
        $this->assertSame(2, substr_count($html, '</ul>'));
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('<strong>Heading</strong>', $html);
        $this->assertStringContainsString("\u{200B}", CvDocument::text(str_repeat('x', 100)));
        $this->assertNull(CvDocument::url('javascript:alert(1)'));
        $this->assertNull(CvDocument::url('file:///etc/passwd'));
        $this->assertSame('https://example.test', CvDocument::url('https://example.test'));
    }
}
