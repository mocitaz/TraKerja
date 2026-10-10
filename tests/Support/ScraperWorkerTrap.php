<?php

namespace App\Livewire\Admin;

// An authorization regression must fail the test rather than launch a real queue worker.
function exec(string $command): never
{
    throw new \RuntimeException('Unauthorized worker launch reached native execution');
}

function popen(string $command, string $mode): never
{
    throw new \RuntimeException('Unauthorized worker launch reached native execution');
}
