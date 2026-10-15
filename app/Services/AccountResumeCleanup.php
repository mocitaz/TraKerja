<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

final class AccountResumeCleanup
{
    public function clean(User $user): void
    {
        $disk = Storage::disk('local');
        foreach ($user->resumes()->get() as $resume) {
            $path = $resume->file_path;
            if (!$path || !str_starts_with($path, 'resumes/'.$user->id.'/') || str_contains($path, '..') || str_contains($path, '\\')) {
                throw new \RuntimeException('Invalid private resume path.');
            }
            if ($disk->exists($path) && !$disk->delete($path)) {
                throw new \RuntimeException('Private resume cleanup failed.');
            }
        }
    }
}
