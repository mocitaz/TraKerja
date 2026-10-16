<?php

namespace App\Services;

final class StudioProviderException extends \RuntimeException
{
    public function __construct(public readonly string $reason)
    {
        parent::__construct('AI Studio provider request failed: '.$reason);
    }
}
