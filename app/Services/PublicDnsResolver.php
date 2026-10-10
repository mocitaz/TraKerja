<?php

namespace App\Services;

class PublicDnsResolver
{
    public function resolve(string $host): array
    {
        return $this->lookup($host, []);
    }

    private function lookup(string $host, array $visited): array
    {
        $host = strtolower(rtrim($host, '.'));
        if (in_array($host, $visited, true) || count($visited) >= 8) {
            return [];
        }
        $visited[] = $host;
        $records = @dns_get_record($host, DNS_A | DNS_AAAA | DNS_CNAME);
        if ($records === false) {
            return [];
        }
        $addresses = [];
        foreach ($records as $record) {
            if (isset($record['ip'])) {
                $addresses[] = $record['ip'];
            } elseif (isset($record['ipv6'])) {
                $addresses[] = $record['ipv6'];
            } elseif (isset($record['target'])) {
                $resolved = $this->lookup($record['target'], $visited);
                if ($resolved === []) {
                    return [];
                }
                $addresses = array_merge($addresses, $resolved);
            }
        }
        return array_values(array_unique($addresses));
    }
}
