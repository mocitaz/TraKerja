<?php

namespace App\Services;

/** Thrown from the chunk callback when the browser stopped listening (the user pressed Stop), to end the reply early. */
final class NaraClientStopped extends \RuntimeException
{
    public function __construct(public readonly string $partial = '')
    {
        parent::__construct('The client stopped the reply');
    }
}
