<?php

namespace App\Support;

final class AnalyzerResultContract
{
    public function validate(mixed $data): array
    {
        $invalid = fn () => new \UnexpectedValueException('Respons analisis tidak valid. Silakan coba lagi.');
        if (!is_array($data) || array_is_list($data) || array_key_exists('error', $data)
            || !empty($data['errors']) || (array_key_exists('success', $data) && $data['success'] !== true)
            || in_array(strtolower((string) ($data['status'] ?? '')), ['error', 'failed', 'failure'], true)) {
            throw $invalid();
        }

        $usable = false;
        if (array_key_exists('skor_kecocokan', $data)) {
            $score = $data['skor_kecocokan'];
            if ((!is_int($score) && !is_float($score)) || !is_finite((float) $score) || $score < 0 || $score > 100) {
                throw $invalid();
            }
        }
        if (array_key_exists('ringkasan', $data)) {
            if (!$this->hasText($data['ringkasan'])) throw $invalid();
            $usable = true;
        }
        if (array_key_exists('rencana_aksi', $data)) {
            $actions = $data['rencana_aksi'];
            if (!is_array($actions) || !array_is_list($actions) || count($actions) > 100) throw $invalid();
            foreach ($actions as $action) {
                if (!$this->hasText($action)) throw $invalid();
                $usable = true;
            }
        }
        foreach (['pendidikan', 'pengalaman_kerja', 'pengalaman_organisasi', 'projek',
            'keterampilan', 'prestasi_dan_publikasi', 'pengalaman', 'keahlian'] as $section) {
            if (!array_key_exists($section, $data)) continue;
            if (!is_array($data[$section]) || ($data[$section] !== [] && array_is_list($data[$section]))) throw $invalid();
            foreach (['teks_revisi', 'alasan_perubahan'] as $field) {
                if (!array_key_exists($field, $data[$section])) continue;
                $value = $data[$section][$field];
                if (!is_string($value) || mb_strlen($value) > 20000) throw $invalid();
                $usable = $this->hasText($value) || $usable;
            }
        }
        if (!$usable) throw $invalid();

        return $data;
    }

    private function hasText(mixed $value): bool
    {
        return is_string($value) && mb_strlen($value) <= 20000 && trim(strip_tags($value)) !== '';
    }
}
