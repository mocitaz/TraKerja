@php
        // UMK 2026 Salary Benchmark Data Lookup
        $matchedUmk = null;
        $jsonPath = base_path('data_umk_lengkap_2026.json');
        if (file_exists($jsonPath)) {
            $provincesData = json_decode(file_get_contents($jsonPath), true) ?: [];
            $searchLoc = strtoupper(trim($job->location ?? ''));
            $cleanSearch = preg_replace('/^(KOTA|KABUPATEN|KAB\.)\s+/i', '', $searchLoc);
            
            foreach ($provincesData as $prov) {
                foreach ($prov['daftar_wilayah'] as $wilayah) {
                    $cleanWilayah = preg_replace('/^(KOTA|KABUPATEN|KAB\.)\s+/i', '', strtoupper($wilayah['nama_wilayah']));
                    if ($cleanSearch && (str_contains($cleanWilayah, $cleanSearch) || str_contains($cleanSearch, $cleanWilayah))) {
                        $matchedUmk = $wilayah;
                        $matchedUmk['provinsi'] = $prov['nama_provinsi'];
                        break 2;
                    }
                }
            }

        }

        // Role multipliers applied to the stored regional baseline.
        $salaryEstimate = null;
        if ($matchedUmk) {
            $pos = strtolower(trim($job->position ?? ''));
            $lvl = strtolower(trim($job->career_level ?? ''));
            $minimumSalary = $matchedUmk['minimum_salary'];
            
            // Determine base multiplier based on role category
            $minMult = 1.1;
            $maxMult = 1.8;
            
            // High-Paying Tech/IT roles
            if (preg_match('/(developer|engineer|programmer|software|backend|frontend|fullstack|data scientist|analyst|devops|system administrator|sysadmin|network|it|cyber|security|qa|testing|scrum|product manager|ui\/ux|data analyst)/i', $pos)) {
                $minMult = 1.6;
                $maxMult = 3.0;
            }
            // Management / Leadership roles
            elseif (preg_match('/(manager|lead|head|director|vp|vice president|ceo|cto|cfo|coo|chief|supervisor|spv)/i', $pos)) {
                $minMult = 1.8;
                $maxMult = 3.5;
            }
            // Entry / Admin / Services roles
            elseif (preg_match('/(admin|clerk|data entry|customer service|cs|call center|receptionist|cashier|operator|staff|officer|assistant|driver|courier|security|cleaning|waiter|magang|intern)/i', $pos)) {
                $minMult = 1.0;
                $maxMult = 1.35;
            }
            // Standard professional roles
            elseif (preg_match('/(marketing|sales|hr|human resources|finance|accountant|accounting|consultant|legal|lawyer|designer|writer|copywriter|content creator)/i', $pos)) {
                $minMult = 1.2;
                $maxMult = 2.0;
            }
            
            // Adjust based on seniority keywords in title
            if (preg_match('/(senior|sr\.|lead|head|principal)/i', $pos)) {
                $minMult *= 1.5;
                $maxMult *= 2.0;
            } elseif (preg_match('/(junior|jr\.|entry|associate|assistant)/i', $pos)) {
                $minMult *= 0.8;
                $maxMult *= 1.0;
            }
            
            // Adjust based on career level field
            if ($lvl === 'intern') {
                $minMult = 0.3;
                $maxMult = 0.7;
            } elseif ($lvl === 'freelance') {
                $minMult *= 0.7;
                $maxMult *= 1.1;
            }
            
            // Ensure logical constraints
            if ($minMult > $maxMult) {
                $temp = $minMult;
                $minMult = $maxMult;
                $maxMult = $temp;
            }
            
            // Prevent multipliers from dropping below reasonable bounds
            if ($lvl !== 'intern' && !str_contains($pos, 'intern') && !str_contains($pos, 'magang')) {
                $minMult = max(1.0, $minMult);
                $maxMult = max(1.15, $maxMult);
            } else {
                $minMult = max(0.25, $minMult);
                $maxMult = max(0.5, $maxMult);
            }
            
            $salaryEstimate = [
                'min' => $minimumSalary * $minMult,
                'max' => $minimumSalary * $maxMult
            ];
        }
@endphp
@if($matchedUmk)
<details class="job-salary-reference">
    <summary><span>Salary reference <small>Regional data · 2026</small></span><i class="ph ph-caret-down" aria-hidden="true"></i></summary>
    <div class="job-salary-body">
        <p class="job-salary-region">{{ Str::title($matchedUmk['nama_wilayah']) }} · {{ Str::title($matchedUmk['provinsi']) }}</p>
        <dl class="job-salary-values">
            <div><dt>Regional baseline</dt><dd>Rp {{ number_format($matchedUmk['minimum_salary'], 0, ',', '.') }} <span>/ month</span></dd></div>
            <div><dt>Calculated range</dt><dd>Rp {{ number_format($salaryEstimate['min'], 0, ',', '.') }} – {{ number_format($salaryEstimate['max'], 0, ',', '.') }}</dd></div>
        </dl>
        <p class="job-salary-disclaimer">Role-based estimate, not an offer from this employer.</p>
        <details class="job-salary-method">
            <summary>Source &amp; calculation <i class="ph ph-caret-down" aria-hidden="true"></i></summary>
            <div><p>This range uses role-based multipliers on stored regional data.</p><p>Recorded source: {{ $matchedUmk['sumber_sk'] ?? 'Not recorded' }}. This reference has not been independently verified here.</p></div>
        </details>
    </div>
</details>
@endif
