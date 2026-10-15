<?php

namespace App\Http\Controllers;

use App\Services\JobListingExtractor;
use App\Services\JobPageFetcher;
use App\Services\ScraperPageFetcher;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JobScraperController extends Controller
{
    public function scrapeUrl(Request $request)
    {
        $request->validate(['url' => 'required|url|max:2048']);
        $url = $request->string('url')->toString();
        $result = null;
        try {
            $response = app(JobPageFetcher::class)->get($url);
            if ($response->successful()) {
                $result = app(JobListingExtractor::class)->extract($response->body(), $url);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception) { /* A configured renderer can retry through the guarded transport. */
        }

        if ((! ($result['success'] ?? false) || empty($result['company_name']) || empty($result['job_title']))
            && config('scraper.microservice_url') && config('scraper.service_token')) {
            try {
                $page = app(ScraperPageFetcher::class)->fetch($url);
                $rendered = app(JobListingExtractor::class)->extract($page['html'], $url);
                if ($rendered['success'] ?? false) {
                    $result = $rendered;
                }
            } catch (ValidationException $e) {
                throw $e;
            } catch (\Exception) { /* Keep any usable direct result instead of replacing it with an error. */
            }
        }
        if (! ($result['success'] ?? false)) {
            return response()->json(['success' => false, 'message' => $result['message'] ??
                'This portal could not be read. Use a job detail URL, enter details manually, or use the extension on the open listing.'], 422);
        }

        return response()->json($result);
    }

    public function scrapeHtml(Request $request)
    {
        $request->validate(['html' => 'required|string|max:2097152', 'url' => 'required|url|max:2048']);
        $result = app(JobListingExtractor::class)->extract($request->input('html'), $request->input('url'));

        return response()->json($result, ($result['success'] ?? false) ? 200 : 422);
    }
}
