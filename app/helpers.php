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
        $html = '<div style="font-family: \'Plus Jakarta Sans\', sans-serif; word-break: break-word;">';
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === "") {
                $html .= '<div style="height:14px;"></div>';
                continue;
            }

            // Parse Inline Markdown
            // **bold** -> <strong>
            $formatted = preg_replace('/\*\*([^*]+)\*\*/', '<strong style="font-weight:800;color:#0f172a;">$1</strong>', e($trimmed));
            
            // *[estimasi]* -> Badge
            $formatted = preg_replace('/\*\[estimasi\]\*/', '<span style="display:inline-flex;padding:1px 6px;background-color:#fff1f2;color:#e11d48;border-radius:4px;font-size:0.75em;font-weight:700;margin-left:4px;text-transform:uppercase;">Estimasi</span>', $formatted);

            // Classify & Wrap
            if (preg_match('/^\*\*[^*]+\*\*$/', $trimmed)) {
                // Header
                $html .= "<p style=\"font-weight:800;color:#0f172a;margin:12px 0 4px 0;font-size:1em;line-height:1.4;letter-spacing:-0.01em;\">{$formatted}</p>";
            } elseif (preg_match('/^[^a-zA-Z0-9\s]*[•\-\*\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}]\s*/u', $trimmed)) {
                // Bullet (Supports common bullet chars including UTF-8 variants, aggressively cleaning leading non-chars)
                $content = preg_replace('/^[^a-zA-Z0-9\s]*[•\-\*\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}]\s*/u', '', $formatted);
                $html .= "<div style=\"display:flex;align-items:flex-start;gap:10px;margin-bottom:6px;\">
                    <span style=\"margin-top:8px;flex-shrink:0;width:5px;height:5px;background-color:#64748b;border-radius:50%;\"></span>
                    <p style=\"margin:0;color:#334155;line-height:1.6;font-size:0.95em;\">{$content}</p>
                </div>";
            } else {
                // Plain Text
                $html .= "<p style=\"color:#475569;line-height:1.6;font-size:0.95em;margin:0 0 8px 0;\">{$formatted}</p>";
            }
        }

        $html .= '</div>';
        return $html;
    }
}

