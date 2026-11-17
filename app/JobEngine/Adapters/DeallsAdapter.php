<?php

namespace App\JobEngine\Adapters;

/** Dealls: ~16.5k /loker/ URLs in sitemap-dynamic.xml, each page with a full JobPosting (ID, IDR salary, company domain). */
class DeallsAdapter extends SitemapJsonLdAdapter
{
    public function slug(): string
    {
        return 'dealls';
    }

    protected function vacancyUrlPattern(): string
    {
        return '#^https://dealls\.com/loker/[^/]+$#';
    }
}
