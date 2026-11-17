<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * A job that runs longer than the queue's retry_after is picked up again by another worker while it is still
 * running, and Laravel then fails it with MaxAttemptsExceededException (seen with DiscoverSourceJob).
 */
class QueueTimeoutTest extends TestCase
{
    public function test_every_job_timeout_stays_below_the_database_queue_retry_after(): void
    {
        $retryAfter = (int) config('queue.connections.database.retry_after');

        foreach (glob(app_path('Jobs/*.php')) as $file) {
            $class = 'App\\Jobs\\'.basename($file, '.php');
            $timeout = (new \ReflectionClass($class))->getDefaultProperties()['timeout'] ?? null;
            if ($timeout) {
                $this->assertLessThan($retryAfter, $timeout, "{$class}::\$timeout must be below retry_after ({$retryAfter}s)");
            }
        }
    }
}
