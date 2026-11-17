<?php

namespace App\JobEngine\Normalize;

/**
 * Removes personal contact details (phone/WhatsApp numbers, email addresses) from vacancy text
 * before it is stored (UU PDP 27/2022). Applicants apply through the source, so nothing is lost.
 */
class PiiScrubber
{
    public static function scrub(?string $text): ?string
    {
        if ($text === null) {
            return null;
        }
        $text = preg_replace('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', '[email disembunyikan]', $text);
        $text = preg_replace('#(?:https?://)?(?:wa\.me|api\.whatsapp\.com/send)\S*#i', '[kontak disembunyikan]', $text);
        // Indonesian mobile/landline numbers: +62/62/0 followed by 8-13 digits with optional separators.
        $text = preg_replace('/(?<![\d])(?:\+?62|0)[\s.-]?\(?\d{2,4}\)?(?:[\s.-]?\d{2,4}){2,4}(?![\d])/', '[nomor disembunyikan]', $text);

        return $text;
    }
}
