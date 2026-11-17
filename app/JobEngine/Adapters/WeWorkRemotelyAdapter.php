<?php

namespace App\JobEngine\Adapters;

use App\JobEngine\Http\PoliteHttpClient;
use App\JobEngine\ScrapedJob;
use App\JobEngine\Vocabulary;
use Illuminate\Support\Carbon;

/** We Work Remotely RSS feed. Item titles are "Company: Job title". */
class WeWorkRemotelyAdapter implements SourceAdapter
{
    public function slug(): string
    {
        return 'weworkremotely';
    }

    public function discover(PoliteHttpClient $http, array $config, int $limit, ?callable $isKnown = null): iterable
    {
        $xml = simplexml_load_string($http->get('https://weworkremotely.com/remote-jobs.rss', timeout: 90)->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
        $yielded = 0;
        foreach ($xml->channel->item ?? [] as $item) {
            $region = (string) $item->region;
            if (! RemoteEligibility::openToIndonesia($region)) {
                continue;
            }
            if ($yielded++ >= $limit) {
                return;
            }
            $titleRaw = (string) $item->title;
            [$company, $title] = str_contains($titleRaw, ':') ? array_map('trim', explode(':', $titleRaw, 2)) : [null, $titleRaw];
            $link = (string) $item->link;

            yield new ScrapedJob(
                source: 'weworkremotely', url: $link, externalId: basename(parse_url($link, PHP_URL_PATH) ?? $link),
                title: $title, company: $company, locationText: $region ?: 'Anywhere', workMode: 'remote',
                employmentType: Vocabulary::employmentType((string) $item->type),
                datePosted: (string) $item->pubDate ? Carbon::parse((string) $item->pubDate)->toDateString() : null,
                description: trim(strip_tags(html_entity_decode((string) $item->description))) ?: null,
                applyUrl: $link, isOpen: true, extractionMethod: 'api',
                raw: ['category' => (string) $item->category, 'region' => $region, 'type' => (string) $item->type],
            );
        }
    }
}
