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
                'Referer' => 'https://www.google.com/',
            ])->timeout(12)->get($url);

            if (!$response->successful()) {
                $status = $response->status();
                $bodySnippet = substr(trim(preg_replace('/\s+/', ' ', strip_tags($response->body()))), 0, 100);
                return response()->json([
                    'success' => false,
                    'message' => "Gagal mengunduh halaman URL lowongan (Status: {$status}). Info: {$bodySnippet}",
                ], 422);
            }

            $html = $response->body();

            $jobTitle = '';
            $companyName = '';
            $description = '';
            $location = '';

            // 1. Try SEEK/Jobstreet Redux Data Parsing (highly specific & complete state payload)
            if (preg_match('/window\.SEEK_REDUX_DATA\s*=\s*(\{.*?\});/is', $html, $reduxMatches)) {
                $data = json_decode($reduxMatches[1], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $jobDetails = $data['jobdetails']['result']['job'] ?? null;
                    if ($jobDetails) {
                        if (!empty($jobDetails['title'])) {
                            $jobTitle = html_entity_decode(strip_tags($jobDetails['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        }
                        if (!empty($jobDetails['advertiser']['name'])) {
                            $companyName = html_entity_decode(strip_tags($jobDetails['advertiser']['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        }
                        if (!empty($jobDetails['location']['label'])) {
                            $location = html_entity_decode(strip_tags($jobDetails['location']['label']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        }
                        if (!empty($jobDetails['content'])) {
                            $description = $this->cleanJobDescriptionMarkup($jobDetails['content']);
                        }
                    }
                }
            }

            // 1b. Try Talentics Vue Data Parsing (specific Vue initialization state)
            if (empty($jobTitle) || empty($companyName)) {
                if (strpos($url, 'talentics.id') !== false) {
                    if (preg_match('/new Vue\(\{\s*el:\s*[\'"]#job-detail-page[\'"],\s*data:\s*(\{.*)/is', $html, $vueMatches)) {
                        $jsBlock = $vueMatches[1];
                        $jobJson = $this->extractBraceBalancedJSON($jsBlock, 'job');
                        $orgJson = $this->extractBraceBalancedJSON($jsBlock, 'organization');
                        
                        if ($jobJson) {
                            $jobData = json_decode($jobJson, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                if (!empty($jobData['title'])) {
                                    $jobTitle = html_entity_decode(strip_tags($jobData['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                                if (!empty($jobData['location'])) {
                                    $location = html_entity_decode(strip_tags($jobData['location']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                            }
                        }
                        
                        if ($orgJson) {
                            $orgData = json_decode($orgJson, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                if (!empty($orgData['company_name'])) {
                                    $companyName = html_entity_decode(strip_tags($orgData['company_name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                } elseif (!empty($orgData['name'])) {
                                    $companyName = html_entity_decode(strip_tags($orgData['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                            }
                        }
                    }
                }
            }

            // 2. Try JSON-LD if not filled by Redux (Search Engine optimized structured data)
            if (empty($jobTitle) || empty($companyName) || empty($description)) {
                $jsonLd = $this->extractJsonLd($html);
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
                    
                    if (!empty($jsonLd['jobLocation'])) {
                        $loc = $jsonLd['jobLocation'];
                        if (is_array($loc)) {
                            if (isset($loc['address']) && is_array($loc['address'])) {
                                $addr = $loc['address'];
                                $parts = [];
                                if (!empty($addr['addressLocality'])) {
                                    $parts[] = $addr['addressLocality'];
                                }
                                if (!empty($addr['addressRegion'])) {
                                    $parts[] = $addr['addressRegion'];
                                }
                                if (!empty($addr['addressCountry'])) {
                                    $parts[] = $addr['addressCountry'];
                                }
                                $location = implode(', ', $parts);
                            } elseif (isset($loc['name'])) {
                                $location = $loc['name'];
                            }
                        } elseif (is_string($loc)) {
                            $location = $loc;
                        }
                    }

                    if (!empty($jsonLd['description'])) {
                        $descText = $jsonLd['description'];
                        $descText = preg_replace('/<(?:br|p|div|li)[^>]*>/i', "\n", $descText);
                        $descText = html_entity_decode(strip_tags($descText), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $descText = preg_replace("/\n+/", "\n\n", $descText);
                        $description = $this->cleanJobDescription($descText);
                    }
                }
            }

            // 2b. Try __NEXT_DATA__ parsing for Next.js-based portals (Dealls, etc.)
            if (empty($jobTitle) || empty($companyName)) {
                if (preg_match('/<script\s+[^>]*?id=["\']__NEXT_DATA__["\'][^>]*?>(.*?)<\/script>/is', $html, $nextMatches)) {
                    $nextData = json_decode($nextMatches[1], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $queries = $nextData['props']['pageProps']['dehydratedState']['queries'] ?? [];
                        foreach ($queries as $query) {
                            $data = $query['state']['data'] ?? null;
                            if (is_array($data)) {
                                if (empty($jobTitle) && !empty($data['title'])) {
                                    $jobTitle = html_entity_decode(strip_tags($data['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                                if (empty($companyName) && !empty($data['company']['name'])) {
                                    $companyName = html_entity_decode(strip_tags($data['company']['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                                if (empty($location) && !empty($data['location'])) {
                                    $loc = $data['location'];
                                    if (is_array($loc)) {
                                        $locParts = [];
                                        if (!empty($loc['city'])) $locParts[] = $loc['city'];
                                        if (!empty($loc['country'])) $locParts[] = $loc['country'];
                                        $location = implode(', ', $locParts);
                                    } else {
                                        $location = $loc;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Extract from HTML markup containers if description is empty or too short (common on LinkedIn guest page)
            if (strlen($description) < 200) {
                $containers = [
                    '/class="show-more-less-html__markup[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="description__text[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="[^"]*job-description[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/id="[^"]*job-description[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="[^"]*JobDescription[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="[^"]*jobDescription[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/data-automation="jobDescription"[^>]*>\s*(.*?)\s*<\/div>/is',
                    '/data-automation="jobAdDetails"[^>]*>\s*(.*?)\s*<\/div>/is'
                ];
                
                foreach ($containers as $pattern) {
                    if (preg_match($pattern, $html, $descMatches)) {
                        $candidate = trim($descMatches[1]);
                        if (strlen(strip_tags($candidate)) > 200) {
                            $description = $this->cleanJobDescriptionMarkup($candidate);
                            break;
                        }
                    }
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
                            $location = trim($locMatches[2]);
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
                        if (empty($location) && !empty($companyNameCandidate)) {
                            $location = $companyNameCandidate;
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
                    $description = $this->cleanJobDescription($descText);
                }
            }

            // Clean up suffixes from Job Title, Company Name, and Location (e.g. "Software Engineer | Glints")
            $platforms = ['Glints', 'Jobstreet', 'Indeed', 'Kalibrr', 'Tech in Asia', 'LinkedIn', 'Glassdoor', 'TechInAsia', 'Karir.com', 'JobSDB'];
            foreach ($platforms as $platform) {
                $jobTitle = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $jobTitle);
                $companyName = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $companyName);
                $location = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $location);
            }

            // Final trim & fallback to Domain name for company if still empty
            $jobTitle = trim($jobTitle);
            $companyName = trim($companyName);
            $location = trim($location);
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
                'location' => $location,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data lowongan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function scrapeHtml(Request $request)
    {
        $request->validate([
            'html' => 'required|string',
            'url' => 'required|url',
        ]);

        $html = $request->input('html');
        $url = $request->input('url');

        try {
            $jobTitle = '';
            $companyName = '';
            $description = '';
            $location = '';

            // 1. Try SEEK/Jobstreet Redux Data Parsing (highly specific & complete state payload)
            if (preg_match('/window\.SEEK_REDUX_DATA\s*=\s*(\{.*?\});/is', $html, $reduxMatches)) {
                $data = json_decode($reduxMatches[1], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $jobDetails = $data['jobdetails']['result']['job'] ?? null;
                    if ($jobDetails) {
                        if (!empty($jobDetails['title'])) {
                            $jobTitle = html_entity_decode(strip_tags($jobDetails['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        }
                        if (!empty($jobDetails['advertiser']['name'])) {
                            $companyName = html_entity_decode(strip_tags($jobDetails['advertiser']['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        }
                        if (!empty($jobDetails['location']['label'])) {
                            $location = html_entity_decode(strip_tags($jobDetails['location']['label']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        }
                        if (!empty($jobDetails['content'])) {
                            $description = $this->cleanJobDescriptionMarkup($jobDetails['content']);
                        }
                    }
                }
            }

            // 1b. Try Talentics Vue Data Parsing (specific Vue initialization state)
            if (empty($jobTitle) || empty($companyName)) {
                if (strpos($url, 'talentics.id') !== false) {
                    if (preg_match('/new Vue\(\{\s*el:\s*[\'"]#job-detail-page[\'"],\s*data:\s*(\{.*)/is', $html, $vueMatches)) {
                        $jsBlock = $vueMatches[1];
                        $jobJson = $this->extractBraceBalancedJSON($jsBlock, 'job');
                        $orgJson = $this->extractBraceBalancedJSON($jsBlock, 'organization');
                        
                        if ($jobJson) {
                            $jobData = json_decode($jobJson, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                if (!empty($jobData['title'])) {
                                    $jobTitle = html_entity_decode(strip_tags($jobData['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                                if (!empty($jobData['location'])) {
                                    $location = html_entity_decode(strip_tags($jobData['location']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                            }
                        }
                        
                        if ($orgJson) {
                            $orgData = json_decode($orgJson, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                if (!empty($orgData['company_name'])) {
                                    $companyName = html_entity_decode(strip_tags($orgData['company_name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                } elseif (!empty($orgData['name'])) {
                                    $companyName = html_entity_decode(strip_tags($orgData['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                            }
                        }
                    }
                }
            }

            // 2. Try JSON-LD if not filled by Redux (Search Engine optimized structured data)
            if (empty($jobTitle) || empty($companyName) || empty($description)) {
                $jsonLd = $this->extractJsonLd($html);
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
                    
                    if (!empty($jsonLd['jobLocation'])) {
                        $loc = $jsonLd['jobLocation'];
                        if (is_array($loc)) {
                            if (isset($loc['address']) && is_array($loc['address'])) {
                                $addr = $loc['address'];
                                $parts = [];
                                if (!empty($addr['addressLocality'])) {
                                    $parts[] = $addr['addressLocality'];
                                }
                                if (!empty($addr['addressRegion'])) {
                                    $parts[] = $addr['addressRegion'];
                                }
                                if (!empty($addr['addressCountry'])) {
                                    $parts[] = $addr['addressCountry'];
                                }
                                $location = implode(', ', $parts);
                            } elseif (isset($loc['name'])) {
                                $location = $loc['name'];
                            }
                        } elseif (is_string($loc)) {
                            $location = $loc;
                        }
                    }

                    if (!empty($jsonLd['description'])) {
                        $descText = $jsonLd['description'];
                        $descText = preg_replace('/<(?:br|p|div|li)[^>]*>/i', "\n", $descText);
                        $descText = html_entity_decode(strip_tags($descText), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                        $descText = preg_replace("/\n+/", "\n\n", $descText);
                        $description = $this->cleanJobDescription($descText);
                    }
                }
            }

            // 2b. Try __NEXT_DATA__ parsing for Next.js-based portals (Dealls, etc.)
            if (empty($jobTitle) || empty($companyName)) {
                if (preg_match('/<script\s+[^>]*?id=["\']__NEXT_DATA__["\'][^>]*?>(.*?)<\/script>/is', $html, $nextMatches)) {
                    $nextData = json_decode($nextMatches[1], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $queries = $nextData['props']['pageProps']['dehydratedState']['queries'] ?? [];
                        foreach ($queries as $query) {
                            $data = $query['state']['data'] ?? null;
                            if (is_array($data)) {
                                if (empty($jobTitle) && !empty($data['title'])) {
                                    $jobTitle = html_entity_decode(strip_tags($data['title']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                                if (empty($companyName) && !empty($data['company']['name'])) {
                                    $companyName = html_entity_decode(strip_tags($data['company']['name']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                }
                                if (empty($location) && !empty($data['location'])) {
                                    $loc = $data['location'];
                                    if (is_array($loc)) {
                                        $locParts = [];
                                        if (!empty($loc['city'])) $locParts[] = $loc['city'];
                                        if (!empty($loc['country'])) $locParts[] = $loc['country'];
                                        $location = implode(', ', $locParts);
                                    } else {
                                        $location = $loc;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Extract from HTML markup containers if description is empty or too short (common on LinkedIn guest page)
            if (strlen($description) < 200) {
                $containers = [
                    '/class="show-more-less-html__markup[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="description__text[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="[^"]*job-description[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/id="[^"]*job-description[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="[^"]*JobDescription[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/class="[^"]*jobDescription[^"]*"\s*>\s*(.*?)\s*<\/div>/is',
                    '/data-automation="jobDescription"[^>]*>\s*(.*?)\s*<\/div>/is',
                    '/data-automation="jobAdDetails"[^>]*>\s*(.*?)\s*<\/div>/is'
                ];
                
                foreach ($containers as $pattern) {
                    if (preg_match($pattern, $html, $descMatches)) {
                        $candidate = trim($descMatches[1]);
                        if (strlen(strip_tags($candidate)) > 200) {
                            $description = $this->cleanJobDescriptionMarkup($candidate);
                            break;
                        }
                    }
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
                            $location = trim($locMatches[2]);
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
                        if (empty($location) && !empty($companyNameCandidate)) {
                            $location = $companyNameCandidate;
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
                    $description = $this->cleanJobDescriptionMarkup($descText);
                }
            }

            // Clean up suffixes from Job Title, Company Name, and Location (e.g. "Software Engineer | Glints")
            $platforms = ['Glints', 'Jobstreet', 'Indeed', 'Kalibrr', 'Tech in Asia', 'LinkedIn', 'Glassdoor', 'TechInAsia', 'Karir.com', 'JobSDB'];
            foreach ($platforms as $platform) {
                $jobTitle = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $jobTitle);
                $companyName = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $companyName);
                $location = preg_replace('/\s*[\|\-\:]\s*' . preg_quote($platform, '/') . '/i', '', $location);
            }

            // Final trim & fallback to Domain name for company if still empty
            $jobTitle = trim($jobTitle);
            $companyName = trim($companyName);
            $location = trim($location);
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
                'location' => $location,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses HTML lowongan: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function cleanJobDescription(string $desc): string
    {
        // 1. Remove LinkedIn/platform posting timestamps (e.g. "Posted 3:30:47 AM.", "Posted 3 days ago.", etc.)
        $desc = preg_replace('/^(?:Posted|Lived|Aktif)\s+[^.]+?\.\s*/iu', '', $desc);
        
        // 2. Fix squashed section headers (e.g. "Job DescriptionDeliver" -> "Job Description:\n\nDeliver")
        $desc = preg_replace('/Job Description\s*([A-Za-z])/iu', "Job Description:\n\n$1", $desc);
        $desc = preg_replace('/About the job\s*([A-Za-z])/iu', "About the Job:\n\n$1", $desc);
        $desc = preg_replace('/Key Responsibilities\s*([A-Za-z])/iu', "Key Responsibilities:\n\n$1", $desc);
        $desc = preg_replace('/Requirements\s*([A-Za-z])/iu', "Requirements:\n\n$1", $desc);
        
        // 3. Strip platform footer promotional text
        $desc = preg_replace('/(?:See this and similar jobs|See jobs like this|Apply now|Apply online|Lihat lowongan kerja ini|Hubungkan dengan).*?$/iu', '', $desc);
        
        // 4. Clean up trailing ellipsis, spaces, and punctuation
        $desc = rtrim($desc, " \t\n\r\0\x0B.…”…");

        // 5. Standardize typical bullet point characters (-, *, small square, checkmark) at the start of any line to a standard bullet point (•)
        $desc = preg_replace('/^\s*[\-\*▪◦✓•]\s+/mu', '• ', $desc);
        
        // 6. Clean up any trailing space or extra newlines immediately following bullet characters
        $desc = preg_replace('/•\s+/u', '• ', $desc);
        
        // 7. Make consecutive bullet items single-spaced for higher information density
        $desc = preg_replace('/(•[^\n]+)\n+(?=\s*•)/u', "$1\n", $desc);
        
        return trim($desc);
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

    private function cleanJobDescriptionMarkup(string $markup): string
    {
        // Replace list items with bullet points
        $markup = preg_replace('/<li[^>]*>/i', "\n• ", $markup);
        $markup = preg_replace('/<\/li>/i', '', $markup);
        
        // Replace paragraph and break tags with newlines
        $markup = preg_replace('/<(?:br|p|div|ul|ol)[^>]*>/i', "\n", $markup);
        
        // Strip other tags
        $markup = strip_tags($markup);
        
        // Decode HTML entities
        $markup = html_entity_decode($markup, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Normalize consecutive newlines and spaces
        $markup = preg_replace("/\n+/", "\n\n", $markup);
        $markup = preg_replace('/[ \t]+/', ' ', $markup);
        
        return $this->cleanJobDescription($markup);
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

    private function extractBraceBalancedJSON(string $str, string $key): ?string
    {
        $pos = strpos($str, $key . ':');
        if ($pos === false) {
            $pos = strpos($str, '"' . $key . '":');
        }
        if ($pos === false) {
            $pos = strpos($str, "'" . $key . "':");
        }
        if ($pos === false) return null;
        
        $startPos = strpos($str, '{', $pos);
        if ($startPos === false) return null;
        
        $len = strlen($str);
        $braceCount = 0;
        
        for ($i = $startPos; $i < $len; $i++) {
            $char = $str[$i];
            if ($char === '{') {
                $braceCount++;
            } elseif ($char === '}') {
                $braceCount--;
                if ($braceCount === 0) {
                    return substr($str, $startPos, $i - $startPos + 1);
                }
            }
        }
        return null;
    }
}
