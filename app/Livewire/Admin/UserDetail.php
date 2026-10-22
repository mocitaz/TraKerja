<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserDetail extends Component
{
    public User $user;

    // Profile form
    public string $name = '';
    public string $email = '';
    public bool $is_admin = false;
    public bool $is_premium = false;

    // AI Quotas form
    public int $ai_credits = 0;
    public int $cl_credits = 0;
    public int $photo_credits = 0;

    // Modals
    public bool $showSendEmailModal = false;
    public string $emailType = 'welcome';
    public bool $showPremiumConfirmModal = false;
    public bool $showDeleteConfirmModal = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->loadUserData();
    }

    public function loadUserData(): void
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->is_admin = (bool) ($this->user->is_admin || $this->user->role === 'admin');
        $this->is_premium = (bool) $this->user->is_premium;

        $this->ai_credits = (int) ($this->user->ai_credits ?? 0);
        $this->cl_credits = (int) ($this->user->cl_credits ?? 0);
        $this->photo_credits = (int) ($this->user->photo_credits ?? 0);
    }

    public function updateUser(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'is_admin' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        $wasPremium = (bool) $this->user->is_premium;
        $newPremium = (bool) $this->is_premium;

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->is_admin = $this->is_admin;
        $this->user->role = $this->is_admin ? 'admin' : 'user';

        if ($wasPremium !== $newPremium) {
            $this->user->is_premium = $newPremium;
            $this->user->payment_status = $newPremium ? User::PAYMENT_STATUS_PAID : User::PAYMENT_STATUS_FREE;
            if ($newPremium) {
                $this->user->premium_purchased_at = now();
                $this->user->premium_until = now()->addDays((int) config('pakasir.premium_duration_days', 365));
            } else {
                $this->user->premium_until = null;
            }
        }

        $this->user->save();

        Log::info('Admin updated user details from detail page', [
            'admin_id' => Auth::id(),
            'user_id' => $this->user->id,
        ]);

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Profile Updated'),
            'message' => __('Member information has been saved successfully.'),
        ]);
    }

    public function saveAiQuotas(): void
    {
        $this->validate([
            'ai_credits' => 'required|integer|min:0',
            'cl_credits' => 'required|integer|min:0',
            'photo_credits' => 'required|integer|min:0',
        ]);

        $this->user->update([
            'ai_credits' => $this->ai_credits,
            'cl_credits' => $this->cl_credits,
            'photo_credits' => $this->photo_credits,
        ]);

        Log::info('Admin updated user AI quotas from detail page', [
            'admin_id' => Auth::id(),
            'user_id' => $this->user->id,
            'quotas' => [
                'ai' => $this->ai_credits,
                'cl' => $this->cl_credits,
                'photo' => $this->photo_credits,
            ],
        ]);

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Quotas Updated'),
            'message' => __('AI feature quotas saved successfully.'),
        ]);
    }

    public function resetAllAiQuotas(): void
    {
        $analyzerLimit = Setting::getLimit('ai_analyzer', $this->user);
        $clLimit = Setting::getLimit('cover_letters', $this->user);
        $photoLimit = Setting::getLimit('ai_photo', $this->user);

        $this->user->update([
            'ai_credits' => $analyzerLimit === 'unlimited' ? 9999 : (int) $analyzerLimit,
            'ai_analyzer_count_this_month' => 0,
            'has_used_ai_analyzer_trial' => false,
            'last_ai_analyzer_reset' => now(),

            'cl_credits' => $clLimit === 'unlimited' ? 9999 : (int) $clLimit,
            'photo_credits' => $photoLimit === 'unlimited' ? 9999 : (int) $photoLimit,
        ]);

        $this->ai_credits = $this->user->ai_credits;
        $this->cl_credits = $this->user->cl_credits;
        $this->photo_credits = $this->user->photo_credits;

        Log::info('Admin reset user AI quotas to default from detail page', [
            'admin_id' => Auth::id(),
            'user_id' => $this->user->id,
        ]);

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Quotas Reset'),
            'message' => __('All AI quotas reset to plan defaults.'),
        ]);
    }

    public function togglePremium(): void
    {
        $this->user->is_premium = !$this->user->is_premium;
        $this->user->payment_status = $this->user->is_premium ? User::PAYMENT_STATUS_PAID : User::PAYMENT_STATUS_FREE;

        if ($this->user->is_premium) {
            $this->user->premium_purchased_at = now();
            $this->user->premium_until = now()->addDays((int) config('pakasir.premium_duration_days', 365));
        } else {
            $this->user->premium_until = null;
        }

        $this->user->save();

        // Refresh quotas
        $analyzerLimit = Setting::getLimit('ai_analyzer', $this->user);
        $clLimit = Setting::getLimit('cover_letters', $this->user);
        $photoLimit = Setting::getLimit('ai_photo', $this->user);

        $this->user->update([
            'ai_credits' => $analyzerLimit === 'unlimited' ? 9999 : (int) $analyzerLimit,
            'cl_credits' => $clLimit === 'unlimited' ? 9999 : (int) $clLimit,
            'photo_credits' => $photoLimit === 'unlimited' ? 9999 : (int) $photoLimit,
        ]);

        $this->loadUserData();
        $this->showPremiumConfirmModal = false;

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => __('Membership Updated'),
            'message' => __('User is now ') . ($this->user->is_premium ? 'PREMIUM' : 'FREE') . '.',
        ]);
    }

    public function openSendEmailModal(): void
    {
        $this->emailType = 'welcome';
        $this->showSendEmailModal = true;
    }

    public function closeSendEmailModal(): void
    {
        $this->showSendEmailModal = false;
    }

    public function sendEmail(): void
    {
        $templates = [
            'welcome' => \App\Mail\WelcomeMail::class,
            'verification_reminder' => \App\Mail\EmailVerificationReminderMail::class,
            'job_reminder' => \App\Mail\JobApplicationReminderMail::class,
            'monthly_motivation' => \App\Mail\MonthlyMotivationMail::class,
            're_engagement' => \App\Mail\ReEngagementMail::class,
            'product_update' => \App\Mail\ProductUpdateMail::class,
            'new_vibe' => \App\Mail\NewVibeMail::class,
            'ai_analyzer' => \App\Mail\AiAnalyzerFreeTrialAnnouncementMail::class,
            'ai_photo' => \App\Mail\AiPhotoAnnouncementMail::class,
            'follow_up_feature' => \App\Mail\FollowUpFeatureAnnouncementMail::class,
            'premium_granted' => \App\Mail\PremiumGrantedMail::class,
            'hiring_season' => \App\Mail\HiringSeasonAlertMail::class,
            'kemerdekaan_ri' => \App\Mail\KemerdekaanRiMail::class,
            'pahlawan' => \App\Mail\PahlawanMail::class,
            'hari_ibu' => \App\Mail\HariIbuMail::class,
            'natal' => \App\Mail\NatalMail::class,
            'tahun_baru_islam' => \App\Mail\TahunBaruIslamMail::class,
        ];
        $this->validate(['emailType' => ['required', \Illuminate\Validation\Rule::in([...array_keys($templates), 'verification'])]]);

        try {
            if ($this->emailType === 'verification') {
                $this->user->sendEmailVerificationNotification();
            } else {
                $mailClass = $templates[$this->emailType];
                Mail::to($this->user)->send(new $mailClass($this->user));
            }

            $this->closeSendEmailModal();

            $this->dispatch('showNotification', [
                'type' => 'success',
                'title' => __('Email Dispatched'),
                'message' => __('Email sent successfully to ') . $this->user->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin failed to send email to user', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);

            $this->dispatch('showNotification', [
                'type' => 'error',
                'title' => __('Failed to Send Email'),
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function confirmDelete(): void
    {
        $this->showDeleteConfirmModal = true;
    }

    public function closeDeleteConfirm(): void
    {
        $this->showDeleteConfirmModal = false;
    }

    public function performDelete()
    {
        if ($this->user->id === Auth::id()) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'title' => __('Action Prohibited'),
                'message' => __('You cannot delete your own active administrator account!'),
            ]);
            $this->closeDeleteConfirm();
            return;
        }

        $userName = $this->user->name;
        $this->user->delete();

        session()->flash('success', "Member {$userName} deleted permanently.");
        return redirect()->route('admin.users');
    }

    public function render()
    {
        $recentApplications = $this->user->jobApplications()
            ->latest('application_date')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        $recentActivities = $this->user->activities()
            ->latest()
            ->orderByDesc('id')
            ->take(10)
            ->get();

        $totalApplications = $this->user->jobApplications()->count();
        $acceptedApplications = $this->user->jobApplications()->where('application_status', 'Accepted')->count();
        $inProgressApplications = $this->user->jobApplications()->where('application_status', 'On Process')->count();
        $declinedApplications = $this->user->jobApplications()
            ->where(function ($q) {
                $q->where('application_status', 'Rejected')
                  ->orWhere('status', 'Rejected');
            })->count();

        return view('livewire.admin.user-detail', [
            'recentApplications' => $recentApplications,
            'recentActivities' => $recentActivities,
            'totalApplications' => $totalApplications,
            'acceptedApplications' => $acceptedApplications,
            'inProgressApplications' => $inProgressApplications,
            'declinedApplications' => $declinedApplications,
        ]);
    }
}
