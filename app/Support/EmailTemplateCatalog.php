<?php

namespace App\Support;

final class EmailTemplateCatalog
{
    public static function groups(): array
    {
        return [
        'Product announcements' => [
            'brand_transformation' => 'Suasana Baru TraKerja (Teman Perjuangan Karier)',
            'product_update' => 'Product update',
            'new_vibe' => 'New workspace',
            'ai_analyzer' => 'AI analyzer',
            'ai_photo' => 'Photo studio',
            'follow_up_feature' => 'Follow-up feature',
            'chrome_extension' => 'Chrome extension',
            'maintenance_completed' => 'Maintenance completed',
        ],
        'Getting started' => [
            'welcome' => 'Welcome',
            'verification' => 'Verify email',
            'verification_reminder' => 'Verification reminder',
        ],
        'Career & engagement' => [
            'job_reminder' => 'Application reminder',
            'monthly_motivation' => 'Monthly motivation',
            'hiring_season' => 'Hiring season',
            're_engagement' => 'Re-engagement',
        ],
        'Celebrations' => [
            'idul_adha' => 'Idul Adha',
            'waisak' => 'Waisak',
            'pancasila' => 'Pancasila Day',
            'tahun_baru_islam' => 'Islamic New Year',
            'kemerdekaan_ri' => 'Independence Day',
            'maulid_nabi' => 'Maulid Nabi',
            'sumpah_pemuda' => 'Youth Pledge Day',
            'pahlawan' => 'Heroes Day',
            'guru_nasional' => 'Teachers Day',
            'hari_ibu' => 'Mother’s Day',
            'natal' => 'Christmas',
        ],
        'Your message' => [
            'custom' => 'Custom email',
        ],
    ];
    }

    public static function mailables(): array
    {
        return [
            'brand_transformation' => \App\Mail\BrandTransformationMail::class,
            'product_update' => \App\Mail\ProductUpdateMail::class,
            'new_vibe' => \App\Mail\NewVibeMail::class,
            'ai_analyzer' => \App\Mail\AiAnalyzerFreeTrialAnnouncementMail::class,
            'ai_photo' => \App\Mail\AiPhotoAnnouncementMail::class,
            'follow_up_feature' => \App\Mail\FollowUpFeatureAnnouncementMail::class,
            'chrome_extension' => \App\Mail\ChromeExtensionPromoMail::class,
            'maintenance_completed' => \App\Mail\MaintenanceCompletedMail::class,
            'welcome' => \App\Mail\WelcomeMail::class,
            'verification_reminder' => \App\Mail\EmailVerificationReminderMail::class,
            'job_reminder' => \App\Mail\JobApplicationReminderMail::class,
            'monthly_motivation' => \App\Mail\MonthlyMotivationMail::class,
            'hiring_season' => \App\Mail\HiringSeasonAlertMail::class,
            're_engagement' => \App\Mail\ReEngagementMail::class,
            'idul_adha' => \App\Mail\IdulAdhaMail::class,
            'waisak' => \App\Mail\WaisakMail::class,
            'pancasila' => \App\Mail\PancasilaMail::class,
            'tahun_baru_islam' => \App\Mail\TahunBaruIslamMail::class,
            'kemerdekaan_ri' => \App\Mail\KemerdekaanRiMail::class,
            'maulid_nabi' => \App\Mail\MaulidNabiMail::class,
            'sumpah_pemuda' => \App\Mail\SumpahPemudaMail::class,
            'pahlawan' => \App\Mail\PahlawanMail::class,
            'guru_nasional' => \App\Mail\GuruNasionalMail::class,
            'hari_ibu' => \App\Mail\HariIbuMail::class,
            'natal' => \App\Mail\NatalMail::class,
        ];
    }
}
