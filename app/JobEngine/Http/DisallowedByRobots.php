<?php

namespace App\JobEngine\Http;

use RuntimeException;

class DisallowedByRobots extends RuntimeException
{
    public function __construct(public readonly string $url)
    {
        parent::__construct("robots.txt disallows {$url}");
    }
}
