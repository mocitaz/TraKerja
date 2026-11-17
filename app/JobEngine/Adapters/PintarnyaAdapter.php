<?php

namespace App\JobEngine\Adapters;

/** Pintarnya: /lowongan/{slug}-{id} URLs with <lastmod> in sitemap/lowongan_N.xml, each page with a JobPosting. */
class PintarnyaAdapter extends SitemapJsonLdAdapter
{
    public function slug(): string
    {
        return 'pintarnya';
    }

    protected function vacancyUrlPattern(): string
    {
        return '#^https://pintarnya\.com/lowongan/[a-z0-9-]+-\d+$#';
    }

    protected function externalIdFromUrl(string $url): ?string
    {
        return preg_match('#-(\d+)$#', $url, $m) ? $m[1] : null;
    }
}
