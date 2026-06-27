<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class JobScraperController extends Controller
{
    public function scrapeUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9,id;q=0.8',
            ])->timeout(8)->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengunduh halaman URL lowongan.',
                ], 422);
            }

            $html = $response->body();

            // Extract Meta Tags
            $ogTitle = $this->getMetaContent($html, 'og:title') ?? $this->getMetaContent($html, 'twitter:title') ?? $this->getTitleTag($html);
            $ogDescription = $this->getMetaContent($html, 'og:description') ?? $this->getMetaContent($html, 'twitter:description') ?? $this->getMetaContent($html, 'description');
            $ogSiteName = $this->getMetaContent($html, 'og:site_name');

            $jobTitle = '';
            $companyName = '';

            if ($ogTitle) {
                // Common title formats: "Position at Company", "Position - Company", "Position | Company"
                $cleanedTitle = html_entity_decode(strip_tags($ogTitle), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                if (Str::contains($cleanedTitle, [' at ', ' AT '])) {
                    $parts = preg_split('/\s+at\s+/i', $cleanedTitle);
                    $jobTitle = trim($parts[0]);
                    $companyName = trim($parts[1] ?? '');
                } elseif (Str::contains($cleanedTitle, ' - ')) {
                    $parts = explode(' - ', $cleanedTitle);
                    $jobTitle = trim($parts[0]);
                    $companyName = trim($parts[1] ?? '');
                } elseif (Str::contains($cleanedTitle, ' | ')) {
                    $parts = explode(' | ', $cleanedTitle);
                    $jobTitle = trim($parts[0]);
                    $companyName = trim($parts[1] ?? '');
                } else {
                    $jobTitle = $cleanedTitle;
                }
            }

            // Clean up company name if it contains platform names
            if ($ogSiteName && empty($companyName)) {
                $companyName = $ogSiteName;
            }

            $description = $ogDescription ? html_entity_decode(strip_tags($ogDescription), ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';

            return response()->json([
                'success' => true,
                'job_title' => $jobTitle,
                'company_name' => $companyName,
                'description' => Str::limit($description, 1000),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data lowongan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getMetaContent(string $html, string $property): ?string
    {
        $pattern = '/<meta\s+[^>]*?(?:name|property)\s*=\s*["\']' . preg_quote($property, '/') . '["\'][^>]*?content\s*=\s*["\']([^"\']+)["\']/i';
        if (preg_match($pattern, $html, $matches)) {
            return trim($matches[1]);
        }

        // Alternative attribute order: content="..." name="..."
        $patternAlt = '/<meta\s+[^>]*?content\s*=\s*["\']([^"\']+)["\'][^>]*?(?:name|property)\s*=\s*["\']' . preg_quote($property, '/') . '["\']/i';
        if (preg_match($patternAlt, $html, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    private function getTitleTag(string $html): ?string
    {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/i', $html, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }
}
