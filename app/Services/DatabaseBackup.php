<?php
namespace App\Services;

use Illuminate\Support\Facades\Process;

final class DatabaseBackup
{
    public function create(): string
    {
        $connection = config('database.connections.'.config('database.default'));
        if (($connection['driver'] ?? config('database.default')) !== 'mysql') {
            throw new \RuntimeException('Only MySQL dumps are supported.');
        }
        $path = tempnam(storage_path('app'), 'db-backup-');
        if ($path === false) throw new \RuntimeException('Cannot create backup file.');
        chmod($path, 0600);
        $handle = fopen($path, 'wb');
        try {
            if (!$handle) throw new \RuntimeException('Cannot open backup file.');
            $command = ['mysqldump', '--single-transaction', '--quick', '--routines', '--events', '--triggers',
                '--comments', '--dump-date', '--host='.(string)($connection['host'] ?? '127.0.0.1'),
                '--port='.(string)($connection['port'] ?? 3306), '--user='.(string)($connection['username'] ?? '')];
            if (!empty($connection['unix_socket'])) $command[] = '--socket='.$connection['unix_socket'];
            $command[] = '--databases';
            $command[] = (string)($connection['database'] ?? '');
            $result = Process::env(['MYSQL_PWD'=>(string)($connection['password'] ?? '')])->timeout(300)
                ->start($command, function ($type, $output) use ($handle) {
                    if ($type === 'out' && fwrite($handle, $output) !== strlen($output)) {
                        throw new \RuntimeException('Cannot write complete backup.');
                    }
                })->wait();
            fclose($handle); $handle = null;
            if (!$result->successful()) throw new \RuntimeException('Database dump process failed.');
            $file = fopen($path, 'rb');
            $hasSchema = false; $lastLine = '';
            try {
                while (($line = fgets($file)) !== false) {
                    if (preg_match('/^CREATE (?:TABLE|DATABASE)\b/', $line)) $hasSchema = true;
                    if (trim($line) !== '') $lastLine = trim($line);
                }
            } finally { fclose($file); }
            if (!$hasSchema || !str_starts_with($lastLine, '-- Dump completed on ')) {
                throw new \RuntimeException('Database dump is empty or incomplete.');
            }
            return $path;
        } catch (\Throwable $e) {
            if (is_resource($handle)) fclose($handle);
            @unlink($path);
            // Process exceptions can contain credentials or SQL; do not expose or chain them.
            throw new \RuntimeException('Database backup could not be completed.');
        }
    }
}
