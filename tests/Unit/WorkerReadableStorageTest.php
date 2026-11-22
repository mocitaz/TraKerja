<?php

namespace Tests\Unit;

use App\Support\WorkerReadableStorage;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Uploads stay private but readable (and removable) by queue workers in the web server's group. */
class WorkerReadableStorageTest extends TestCase
{
    public function test_private_uploads_are_group_readable_and_never_public(): void
    {
        $disk = Storage::disk('local');
        $path = 'ai-studio-sources/test-'.uniqid().'/cv.pdf';
        $disk->put($path, '%PDF-1.4 test');
        @chmod($disk->path(dirname($path)), 0700); // as a web server with a strict umask would leave it
        @chmod($disk->path($path), 0600);

        WorkerReadableStorage::share($path);

        clearstatcache();
        $this->assertSame(0640, fileperms($disk->path($path)) & 0777, 'owner and group read, nobody else');
        $this->assertSame(0770, fileperms($disk->path(dirname($path))) & 0777, 'the group can open and clean up the folder');
        $this->assertSame(0, fileperms($disk->path($path)) & 0007, 'never readable by other users');
        $disk->deleteDirectory(dirname($path));
    }
}
