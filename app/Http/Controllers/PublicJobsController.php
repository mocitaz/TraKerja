<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * Public, indexable pages for vacancies and companies (TraKerja 2.0, Fase 5).
 * Only open vacancies carry schema.org JobPosting markup; closed ones stay reachable but are
 * marked noindex and carry no markup, as Google asks for expired jobs.
 */
class PublicJobsController extends Controller
{
    /** Public job search for guests: /loker?q=&lokasi=&remote=1 */
    public function index(Request $request)
    {
        app()->setLocale('id'); // public SEO pages are written in Indonesian (labels, salary, dates)
        $q = trim(mb_substr((string) $request->query('q', ''), 0, 80));
        $lokasi = trim(mb_substr((string) $request->query('lokasi', ''), 0, 80));
        $remote = $request->boolean('remote');

        $vacancies = Vacancy::visible()->with(['company', 'region', 'listings.source', 'primaryListing.source'])
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('title', 'like', "%{$q}%")
                ->orWhereHas('company', fn ($c) => $c->where('name', 'like', "%{$q}%"))))
            ->when($lokasi !== '', fn ($query) => $query->whereHas('region', fn ($r) => $r->where('name', 'like', "%{$lokasi}%")
                ->orWhereHas('parent', fn ($p) => $p->where('name', 'like', "%{$lokasi}%"))))
            ->when($remote, fn ($query) => $query->where('work_mode', 'remote'))
            ->orderByRaw('COALESCE(date_posted, first_seen_at) desc')
            ->paginate(20)->withQueryString();

        return view('public.index', [
            'vacancies' => $vacancies, 'q' => $q, 'lokasi' => $lokasi, 'remote' => $remote,
            'filtered' => $q !== '' || $lokasi !== '' || $remote,
        ]);
    }

    /** Short social link: /l/158/t goes to the job page tagged utm_source=threads. */
    public function short(int $id, ?string $platform = null)
    {
        $vacancy = Vacancy::find($id);
        if (! $vacancy) {
            return $this->gone(null, 404);
        }
        if ($vacancy->merged_into_id && ($target = Vacancy::find($vacancy->merged_into_id))) {
            $vacancy = $target;
        }
        // Archived or hidden jobs still go to their page, which explains it and suggests similar open jobs.
        $source = \App\JobEngine\Promotion\PromoWriter::SHORT_CODES[$platform] ?? null;
        $query = $source ? '?utm_source='.$source.'&utm_medium=social&utm_campaign=promo' : '';

        return redirect(parse_url($vacancy->public_path, PHP_URL_PATH).$query, 302);
    }

    public function show(string $slug)
    {
        app()->setLocale('id'); // public SEO pages are written in Indonesian (labels, salary, dates)
        // "{slug}-{ulid}": the id is the last 26 characters.
        $publicId = strtolower(substr($slug, -26));
        $vacancy = Vacancy::with(['company', 'region.parent', 'sector', 'listings.source', 'primaryListing.source'])
            ->where('public_id', $publicId)->first();
        if (! $vacancy) {
            return $this->gone(null, 404);
        }

        if ($vacancy->merged_into_id && ($target = Vacancy::find($vacancy->merged_into_id))) {
            return redirect(parse_url($target->public_path, PHP_URL_PATH), 301);
        }
        if ($vacancy->scam_flag === 'suspected' || $vacancy->status === 'archived') {
            return $this->gone($vacancy, 410);
        }

        // Old or mistyped slugs redirect to the current one (compare paths: APP_URL may differ from the request host).
        $canonical = $vacancy->public_path;
        if (parse_url(url()->current(), PHP_URL_PATH) !== parse_url($canonical, PHP_URL_PATH)) {
            return redirect(parse_url($canonical, PHP_URL_PATH), 301);
        }

        $open = $vacancy->status === 'active';

        return response()->view('public.vacancy', [
            'vacancy' => $vacancy,
            'open' => $open,
            'jsonLd' => $open ? $this->jobPosting($vacancy) : null,
            'related' => Vacancy::visible()->with(['company', 'region'])->whereKeyNot($vacancy->id)
                ->where(fn ($q) => $q->where('company_id', $vacancy->company_id)->orWhere('sector_id', $vacancy->sector_id))
                ->latest('first_seen_at')->limit(6)->get(),
        ]);
    }

    /**
     * Friendly page for a job that is gone (archived, hidden as a suspected scam, or unknown), so links shared on
     * social media still lead somewhere useful: similar open jobs and a search box. 410 tells search engines the
     * page is gone for good; noindex keeps it out of results either way.
     */
    private function gone(?Vacancy $vacancy, int $status)
    {
        app()->setLocale('id');
        // A suspected scam shows nothing about the posting itself.
        $known = $vacancy && $vacancy->scam_flag !== 'suspected' ? $vacancy : null;
        $similar = Vacancy::visible()->with(['company', 'region', 'listings.source', 'primaryListing.source'])
            ->when($known, fn ($q) => $q->whereKeyNot($known->id)->where(fn ($w) => $w
                ->where('company_id', $known->company_id)
                ->orWhere(fn ($s) => $s->where('sector_id', $known->sector_id)->when($known->region_id, fn ($r) => $r->where('region_id', $known->region_id)))
                ->orWhere('sector_id', $known->sector_id)))
            ->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->limit(6)->get();
        if ($similar->count() < 3) {
            $similar = $similar->merge(Vacancy::visible()->with(['company', 'region'])->whereNotIn('id', $similar->pluck('id')->push($vacancy?->id ?? 0))
                ->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->limit(6 - $similar->count())->get());
        }

        return response()->view('public.gone', ['vacancy' => $known, 'similar' => $similar, 'notFound' => $status === 404], $status);
    }

    public function company(string $slug)
    {
        app()->setLocale('id'); // public SEO pages are written in Indonesian (labels, salary, dates)
        $company = Company::where('slug', $slug)->firstOrFail();
        $vacancies = Vacancy::visible()->with(['region', 'listings.source', 'primaryListing.source'])
            ->where('company_id', $company->id)->orderByRaw('COALESCE(date_posted, first_seen_at) desc')->paginate(20);

        return view('public.company', ['company' => $company, 'vacancies' => $vacancies]);
    }

    public function sitemap(): Response
    {
        $urls = Vacancy::visible()->orderByDesc('id')->limit(45000)->get(['id', 'slug', 'public_id', 'updated_at'])
            ->map(fn ($v) => ['loc' => $v->public_path, 'lastmod' => $v->updated_at?->toAtomString()]);
        $companies = Company::whereIn('id', Vacancy::visible()->select('company_id'))->get(['slug', 'updated_at'])
            ->map(fn ($c) => ['loc' => route('perusahaan.show', $c->slug), 'lastmod' => $c->updated_at?->toAtomString()]);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml .= '<url><loc>'.e(route('loker.index')).'</loc></url>';
        foreach ($urls->concat($companies) as $url) {
            $xml .= '<url><loc>'.e($url['loc']).'</loc>'.($url['lastmod'] ? '<lastmod>'.$url['lastmod'].'</lastmod>' : '').'</url>';
        }

        return response($xml.'</urlset>', 200, ['Content-Type' => 'application/xml']);
    }

    /** schema.org JobPosting for Google for Jobs. */
    private function jobPosting(Vacancy $vacancy): array
    {
        $province = $vacancy->region?->level === 'city' ? $vacancy->region->parent?->name : $vacancy->region?->name;
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $vacancy->title,
            'description' => e($vacancy->summary ?: $vacancy->title),
            'identifier' => ['@type' => 'PropertyValue', 'name' => 'TraKerja', 'value' => $vacancy->public_id],
            'datePosted' => ($vacancy->date_posted ?? $vacancy->first_seen_at)?->toDateString(),
            'hiringOrganization' => array_filter([
                '@type' => 'Organization',
                'name' => $vacancy->company?->name,
                'sameAs' => $vacancy->company?->domain ? 'https://'.$vacancy->company->domain : null,
                'logo' => $vacancy->company?->logo_url,
            ]),
            'directApply' => false,
            'url' => $vacancy->public_path,
        ];
        if ($vacancy->valid_through) {
            $data['validThrough'] = $vacancy->valid_through->endOfDay()->toAtomString();
        }
        if ($vacancy->employment_type) {
            $data['employmentType'] = [
                'full_time' => 'FULL_TIME', 'part_time' => 'PART_TIME', 'contract' => 'CONTRACTOR',
                'internship' => 'INTERN', 'freelance' => 'OTHER',
            ][$vacancy->employment_type];
        }
        if ($vacancy->work_mode === 'remote') {
            $data['jobLocationType'] = 'TELECOMMUTE';
            $data['applicantLocationRequirements'] = ['@type' => 'Country', 'name' => 'Indonesia'];
        }
        if ($vacancy->region || $vacancy->country) {
            $data['jobLocation'] = ['@type' => 'Place', 'address' => array_filter([
                '@type' => 'PostalAddress',
                'addressLocality' => $vacancy->region?->level === 'city' ? $vacancy->region->name : null,
                'addressRegion' => $province,
                'addressCountry' => $vacancy->country ?? 'ID',
            ])];
        }
        if ($vacancy->salary_min) {
            $data['baseSalary'] = ['@type' => 'MonetaryAmount', 'currency' => $vacancy->salary_currency ?? 'IDR', 'value' => array_filter([
                '@type' => 'QuantitativeValue',
                'minValue' => $vacancy->salary_min,
                'maxValue' => $vacancy->salary_max,
                'unitText' => Str::upper($vacancy->salary_period ?? 'month'),
            ])];
        }

        return $data;
    }
}
