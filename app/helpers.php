<?php

if (!function_exists('is_premium')) {
    /**
     * Check if user is premium
     *
     * @param \App\Models\User|null $user
     * @return bool
     */
    function is_premium($user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }
        
        return $user->isPremium();
    }
}

if (!function_exists('cv_templates_count')) {
    /**
     * Get CV templates count for current user
     *
     * @return int
     */
    function cv_templates_count(): int
    {
        $user = auth()->user();
        
        if (!$user) {
            return 1; // Default free tier
        }
        
        return $user->getCvTemplatesCount();
    }
}

if (!function_exists('format_date_range')) {
    /**
     * Format date range for display
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param bool $isCurrent
     * @return string
     */
    function format_date_range($startDate, $endDate = null, $isCurrent = false): string
    {
        if (!$startDate) {
            return '';
        }
        
        $start = \Carbon\Carbon::parse($startDate)->format('M Y');
        
        if ($isCurrent) {
            return $start . ' - Present';
        }
        
        if ($endDate) {
            $end = \Carbon\Carbon::parse($endDate)->format('M Y');
            return $start . ' - ' . $end;
        }
        
        return $start;
    }
}

if (!function_exists('user_has_feature')) {
    /**
     * Check if user has access to a feature
     *
     * @param string $feature
     * @param \App\Models\User|null $user
     * @return bool
     */
    function user_has_feature(string $feature, $user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return false;
        }
        
        return $user->canAccessFeature($feature);
    }
}

if (!function_exists('format_cv_text')) {
    /**
     * Format raw text into premium CV layout (v4.0)
     * Supports: **bold**, - bullet, *[estimasi]*
     *
     * @param string|null $text
     * @return string
     */
    function format_cv_text($text): string
    {
        if (!$text) return '';

        // Step 1: Preprocess lines (Auto-spacing for headers)
        $rawLines = explode("\n", $text);
        $lines = [];
        foreach ($rawLines as $line) {
            $trimmed = trim($line);
            
            // Auto spacer before headers
            $isPureHeader = preg_match('/^\*\*[^*]+\*\*$/', $trimmed);
            if ($isPureHeader && count($lines) > 0 && trim(end($lines)) !== "") {
                $lines[] = "";
            }
            $lines[] = $line;
        }

        // Step 2: Render each line
        $html = '<div style="word-break: break-word;">';
        $inList = false;
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === "") {
                if ($inList) {
                    $html .= '</ul>';
                    $inList = false;
                }
                $html .= '<div style="height:3px;"></div>';
                continue;
            }

            // Parse Inline Markdown
            // **bold** -> <strong>
            $formatted = preg_replace('/\*\*([^*]+)\*\*/', '<strong style="font-weight:700;color:#0f172a;">$1</strong>', e($trimmed));
            
            // *[estimasi]* -> Badge
            $formatted = preg_replace('/\*\[estimasi\]\*/', '<span style="display:inline-block;padding:1px 4px;background-color:#fff1f2;color:#e11d48;border-radius:3px;font-size:0.75em;font-weight:700;margin-left:4px;text-transform:uppercase;">Estimasi</span>', $formatted);

            // Classify & Wrap
            if (preg_match('/^\*\*[^*]+\*\*$/', $trimmed)) {
                // Header
                if ($inList) {
                    $html .= '</table>';
                    $inList = false;
                }
                $html .= "<p style=\"font-weight:700;color:#0f172a;margin:5px 0 2px 0;font-size:0.95em;line-height:1.35;\">{$formatted}</p>";
            } elseif (preg_match('/^[^a-zA-Z0-9\s]*[•\-\*\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}]\s*/u', $trimmed)) {
                // Bullet (Supports common bullet chars including UTF-8 variants, aggressively cleaning leading non-chars)
                $content = preg_replace('/^[^a-zA-Z0-9\s]*[•\-\*\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}]\s*/u', '', $formatted);
                if (!$inList) {
                    $html .= '<table class="cv-bullet-table" style="width:100%;border-collapse:collapse;margin:1px 0 2px 0;padding:0;border:0;">';
                    $inList = true;
                }
                $html .= '<tr>'
                    . '<td style="width:12px;vertical-align:top;padding:1px 0 1px 0;border:0;line-height:1.35;color:#475569;font-size:8pt;text-align:left;">&#8226;</td>'
                    . '<td style="vertical-align:top;padding:1px 0 1px 0;border:0;line-height:1.35;color:#334155;font-size:0.95em;text-align:left;">' . $content . '</td>'
                    . '</tr>';
            } else {
                // Plain Text
                if ($inList) {
                    $html .= '</table>';
                    $inList = false;
                }
                $html .= "<p style=\"color:#334155;line-height:1.38;font-size:0.95em;margin:0 0 3px 0;\">{$formatted}</p>";
            }
        }

        if ($inList) {
            $html .= '</table>';
        }

        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('get_cv_photo_src')) {
    /**
     * Get user profile photo suitable for both web and DomPDF (base64 for local files)
     */
    function get_cv_photo_src($user): ?string
    {
        if (!$user || empty($user->logo)) {
            return null;
        }

        $logo = $user->logo;

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }

        $localPath = storage_path('app/public/' . $logo);
        if (file_exists($localPath)) {
            $mime = mime_content_type($localPath) ?: 'image/jpeg';
            $data = base64_encode(file_get_contents($localPath));
            return "data:{$mime};base64,{$data}";
        }

        $pubPath = public_path('storage/' . $logo);
        if (file_exists($pubPath)) {
            $mime = mime_content_type($pubPath) ?: 'image/jpeg';
            $data = base64_encode(file_get_contents($pubPath));
            return "data:{$mime};base64,{$data}";
        }

        return asset('storage/' . $logo);
    }
}

