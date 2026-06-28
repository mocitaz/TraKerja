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
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            ])->timeout(12)->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengunduh halaman URL lowongan.',
                ], 422);
            }

            $html = $response->body();

            // Extract using JSON-LD (Search Engine optimized structured data, works on 99% of major job sites)
            $jsonLd = $this->extractJsonLd($html);
            
            $jobTitle = '';
            $companyName = '';
            $description = '';

            if ($jsonLd) {
                if (!empty($jsonLd['title'])) {
                    $jobTitle = html_entity_decode(strip_tags($jsonLd['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                } elseif (!empty($jsonLd['jobTitle'])) {
                    $jobTitle = html_entity_decode(strip_tags($jsonLd['jobTitle']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }
                
                if (!empty($jsonLd['hiringOrganization'])) {
                    $org = $jsonLd['hiringOrganization'];
                    if (is_array($org) && !empty($org['name'])) {
                        $companyName = html_entity_decode(strip_tags($org['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    } elseif (is_string($org)) {
                        $companyName = html_entity_decode(strip_tags($org), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                }
                
                if (!empty($jsonLd['description'])) {
                    $descText = $jsonLd['description'];
                    $descText = preg_replace('/<(?:br|p|div|li)[^>]*>/i', "\n", $descText);
                    $descText = html_entity_decode(strip_tags($descText), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $descText = preg_replace("/\n+/", "\n\n", $descText);
                    $description = trim($descText);
                }
            }

            // Fallback: If JSON-LD didn't yield values, parse Meta & Title Tags
            if (empty($jobTitle) || empty($companyName) || empty($description)) {
                $ogTitle = $this->getMetaContent($html, 'og:title') ?? $this->getMetaContent($html, 'twitter:title') ?? $this->getTitleTag($html);
                $ogDescription = $this->getMetaContent($html, 'og:description') ?? $this->getMetaContent($html, 'twitter:description') ?? $this->getMetaContent($html, 'description');
                $ogSiteName = $this->getMetaContent($html, 'og:site_name');

                if (empty($jobTitle) && $ogTitle) {
                    $cleanedTitle = html_entity_decode(strip_tags($ogTitle), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    
                    // Check if formatted like "Company hiring Position..." (very common in LinkedIn title tag fallbacks)
                    if (preg_match('/(.+?)\s+hiring\s+(.+)/i', $cleanedTitle, $hiringMatches)) {
                        $companyNameCandidate = trim($hiringMatches[1]);
                        $jobTitleRemaining = trim($hiringMatches[2]);
                        
                        if (preg_match('/(.+?)\s+(?:in|at|@)\s+(.+)/i', $jobTitleRemaining, $locMatches)) {
                            $jobTitle = trim($locMatches[1]);
                        } else {
                            $jobTitle = $jobTitleRemaining;
                        }
                        
                        $companyName = $companyNameCandidate;
                    } elseif (preg_match('/\s+(?:at|@|in)\s+(.+)/i', $cleanedTitle, $matches)) {
                        $companyNameCandidate = trim($matches[1]);
                        $jobTitleCandidate = trim(preg_replace('/\s+(?:at|@|in)\s+.+/i', '', $cleanedTitle));
                        
                        // Clean up platform name suffixes (e.g. "Glints", "Jobstreet") from company candidate
                        if ($ogSiteName) {
                            $companyNameCandidate = str_ireplace($ogSiteName, '', $companyNameCandidate);
                            $companyNameCandidate = trim(trim($companyNameCandidate, '-|/ '));
                        }
                        
                        $jobTitle = $jobTitleCandidate;
                        if (empty($companyName) && !empty($companyNameCandidate)) {
                            $companyName = $companyNameCandidate;
                        }
                    } elseif (Str::contains($cleanedTitle, ' - ')) {
                        $parts = explode(' - ', $cleanedTitle);
                        $jobTitle = trim($parts[0]);
                        if (empty($companyName) && !empty($parts[1])) {
                            $companyName = trim($parts[1]);
                        }
                    } elseif (Str::contains($cleanedTitle, ' | ')) {
                        $parts = explode(' | ', $cleanedTitle);
                        $jobTitle = trim($parts[0]);
                        if (empty($companyName) && !empty($parts[1])) {
                            $companyName = trim($parts[1]);
                        }
                    } else {
                        $jobTitle = $cleanedTitle;
                    }
                }

                if (empty($companyName) && $ogSiteName) {
                    $companyName = html_entity_decode(strip_tags($ogSiteName), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                }

                if (empty($description) && $ogDescription) {
                    $descText = preg_replace('/<(?:br|p|div|li)[^>]*>/i', "\n", $ogDescription);
                    $descText = html_entity_decode(strip_tags($descText), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $descText = preg_replace("/\n+/", "\n\n", $descText);
                    $description = trim($descText);
                }
            }

            // Clean up suffixes from Job Title & Company Name (e.g. "Software Engineer | Glints")
            $platforms = ['Glints', 'Jobstreet', 'Indeed', 'Kalibrr', 'Tech in Asia', 'LinkedIn', 'Glassdoor', 'TechInAsia', 'Karir.com', 'JobSDB'];
            foreach ($platforms as $platform) {
                $jobTitle = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $jobTitle);
                $companyName = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $companyName);
            }

            // Final trim & fallback to Domain name for company if still empty
            $jobTitle = trim($jobTitle);
            $companyName = trim($companyName);
            if (empty($companyName)) {
                $host = parse_url($url, PHP_URL_HOST);
                $host = preg_replace('/^www\./i', '', $host);
                $companyName = ucfirst(explode('.', $host)[0]);
            }

            return response()->json([
                'success' => true,
                'job_title' => $jobTitle,
                'company_name' => $companyName,
                'description' => Str::limit($description, 1500),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data lowongan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function extractJsonLd(string $html): ?array
    {
        // Find all script tags containing JSON-LD
        $pattern = '/<script\s+[^>]*?type\s*=\s*["\']application\/ld\+json["\'][^>]*?>(.*?)<\/script>/is';
        if (preg_match_all($pattern, $html, $matches)) {
            foreach ($matches[1] as $jsonText) {
                $jsonText = trim($jsonText);
                if (empty($jsonText)) {
                    continue;
                }
                
                // Decode JSON
                $data = json_decode($jsonText, true);
                if (!is_array($data)) {
                    continue;
                }
                
                // Search for JobPosting @type
                $jobPosting = $this->findJobPosting($data);
                if ($jobPosting) {
                    return $jobPosting;
                }
            }
        }
        return null;
    }

    private function findJobPosting(array $data): ?array
    {
        // Check if current level is a JobPosting
        if (isset($data['@type']) && (
            $data['@type'] === 'JobPosting' || 
            (is_array($data['@type']) && in_array('JobPosting', $data['@type']))
        )) {
            return $data;
        }

        // If it's a list or @graph, search items
        if (isset($data['@graph']) && is_array($data['@graph'])) {
            foreach ($data['@graph'] as $item) {
                if (is_array($item)) {
                    $res = $this->findJobPosting($item);
                    if ($res) {
                        return $res;
                    }
                }
            }
        }

        // Recursive search on arrays
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $res = $this->findJobPosting($val);
                if ($res) {
                    return $res;
                }
            }
        }

        return null;
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
