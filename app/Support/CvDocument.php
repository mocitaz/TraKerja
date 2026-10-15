<?php

namespace App\Support;

final class CvDocument
{
    public static function text(?string $value): string
    {
        // Break long tokens without changing the visible text on the page.
        return e(preg_replace('/(\S{32})(?=\S)/u', '$1'."\u{200B}", $value ?? ''));
    }

    public static function url(?string $value): ?string
    {
        if (!$value || !filter_var($value, FILTER_VALIDATE_URL)) {
            return null;
        }
        return in_array(strtolower((string) parse_url($value, PHP_URL_SCHEME)), ['http', 'https'], true) ? $value : null;
    }

    public static function profilePhoto(?string $path): ?string
    {
        if (!$path || str_contains($path, ':') || str_contains($path, "\0")) return null;
        try {
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            $root = realpath($disk->path(''));
            $file = realpath($disk->path($path));
            if (!$root || !$file || !str_starts_with($file, $root.DIRECTORY_SEPARATOR) || !is_file($file)) return null;
            if (filesize($file) > 2 * 1024 * 1024) return null;
            $size = @getimagesize($file);
            if (!$size || !in_array($size['mime'] ?? '', ['image/jpeg', 'image/png', 'image/gif'], true)
                || $size[0] < 1 || $size[1] < 1 || $size[0] * $size[1] > 12000000) return null;
            $bytes = file_get_contents($file);
            if (!function_exists('imagecreatefromstring')) return 'data:'.$size['mime'].';base64,'.base64_encode($bytes);
            $source = @imagecreatefromstring($bytes);
            if (!$source) return null;
            try {
                // Normalize the portrait to a fixed 5:6 frame; never stretch the source.
                $width = imagesx($source); $height = imagesy($source);
                $cropWidth = min($width, $height * 5 / 6);
                $cropHeight = min($height, $width * 6 / 5);
                $portrait = imagecreatetruecolor(500, 600);
                try {
                    imagefill($portrait, 0, 0, imagecolorallocate($portrait, 255, 247, 237));
                    imagecopyresampled($portrait, $source, 0, 0, (int)(($width - $cropWidth) / 2), (int)(($height - $cropHeight) / 2), 500, 600, (int)$cropWidth, (int)$cropHeight);
                    ob_start();
                    imagejpeg($portrait, null, 90);
                    $result = ob_get_clean();
                    return 'data:image/jpeg;base64,'.base64_encode($result);
                } finally { imagedestroy($portrait); }
            } finally { imagedestroy($source); }
        } catch (\Throwable) {
            return null;
        }
    }

    public static function description(?string $value): string
    {
        $html = '';
        $list = false;
        foreach (preg_split('/\R/u', $value ?? '') as $line) {
            $line = trim($line);
            if ($line === '') {
                if ($list) { $html .= '</ul>'; $list = false; }
                continue;
            }
            $bullet = preg_match('/^(?:[-*•‣])\s+(.+)$/u', $line, $match);
            $content = self::text($bullet ? $match[1] : $line);
            $content = preg_replace('/\*\*([^*]+)\*\*/u', '<strong>$1</strong>', $content);
            if ($bullet) {
                if (!$list) { $html .= '<ul>'; $list = true; }
                $html .= '<li>'.$content.'</li>';
            } else {
                if ($list) { $html .= '</ul>'; $list = false; }
                $html .= '<p>'.$content.'</p>';
            }
        }
        return $html.($list ? '</ul>' : '');
    }
}
