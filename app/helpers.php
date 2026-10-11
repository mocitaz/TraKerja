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
                    $html .= '</ul>';
                    $inList = false;
                }
                $html .= "<p style=\"font-weight:700;color:#0f172a;margin:6px 0 2px 0;font-size:0.95em;line-height:1.35;\">{$formatted}</p>";
            } elseif (preg_match('/^[^a-zA-Z0-9\s]*[•\-\*\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}]\s*/u', $trimmed)) {
                // Bullet (Supports common bullet chars including UTF-8 variants, aggressively cleaning leading non-chars)
                $content = preg_replace('/^[^a-zA-Z0-9\s]*[•\-\*\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}]\s*/u', '', $formatted);
                if (!$inList) {
                    $html .= '<ul style="margin:2px 0 3px 0;padding:0 0 0 14px;list-style-type:disc;">';
                    $inList = true;
                }
                $html .= "<li style=\"margin:0 0 2px 0;padding:0;line-height:1.38;color:#334155;font-size:0.95em;\">{$content}</li>";
            } else {
                // Plain Text
                if ($inList) {
                    $html .= '</ul>';
                    $inList = false;
                }
                $html .= "<p style=\"color:#475569;line-height:1.42;font-size:0.95em;margin:0 0 4px 0;\">{$formatted}</p>";
            }
        }

        if ($inList) {
            $html .= '</ul>';
        }

        $html .= '</div>';
        return $html;
    }
}

