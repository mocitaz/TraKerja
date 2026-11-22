<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Uploads are written by the web server (www-data) and processed by queue workers that run as another user in the
 * www-data group. Private files stay closed to everyone else, but the group must be able to read them and remove
 * them when the work is done. The web server's umask can strip group bits from new folders, so they are set here.
 */
final class WorkerReadableStorage
{
    public static function share(string $relativePath): void
    {
        $disk = Storage::disk('local');
        $root = rtrim($disk->path(''), DIRECTORY_SEPARATOR);
        $file = $disk->path($relativePath);
        if (is_file($file)) {
            @chmod($file, 0640);
        }
        // Every folder between the file and the disk root (not the root itself).
        for ($dir = dirname($file); str_starts_with($dir, $root.DIRECTORY_SEPARATOR); $dir = dirname($dir)) {
            if (is_dir($dir) && (fileperms($dir) & 0770) !== 0770) {
                @chmod($dir, 0770);
            }
        }
    }
}
