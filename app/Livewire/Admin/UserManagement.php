<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Mail\AiAnalyzerFreeTrialAnnouncementMail;
use App\Mail\EmailVerificationReminderMail;
use App\Mail\HiringSeasonAlertMail;
use App\Mail\JobApplicationReminderMail;
use App\Mail\MonthlyMotivationMail;
use App\Mail\PremiumGrantedMail;
use App\Mail\ReEngagementMail;
use App\Mail\WelcomeMail;
use App\Mail\FollowUpFeatureAnnouncementMail;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UserManagement extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterPremium = 'all'; // all, premium, free
    public $filterRole = 'all'; // all, admin, user
    public $filterAiAnalyzer = 'all'; // all, used, not_used
    public $perPage = 10; // supports 10, 20, 50, 100, 'all'
    
    public $showEditModal = false;
    public $showCreateAdminModal = false;
    public $showSendEmailModal = false;
    public $editingUserId;
    public $editName;
    public $editEmail;
    public $editIsPremium;
    public $editIsAdmin;
    public $editAiCredits = 0;
    public $editClCredits = 0;
    public $editPhotoCredits = 0;
    
    // Create Admin properties
    public $newAdminName;
    public $newAdminEmail;
    public $newAdminPassword;
    public $newAdminPasswordConfirmation;
    
    // Send Email properties
    public $emailTargetUserId;
    public $emailTargetUserName;
    public $emailTargetUserEmail;
    public $emailType = 'welcome';
    
    // Premium Confirmation properties
    public $showPremiumConfirmModal = false;
    public $confirmTargetUserId;
    public $confirmTargetUserName;
    public $confirmTargetUserIsPremium;

    // Delete Confirmation properties
    public $showDeleteConfirmModal = false;
    public $deleteTargetUserId;
    public $deleteTargetUserName;
    
    protected $listeners = ['refreshUsers' => '$refresh'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilterPremium()
    {
        $this->resetPage();
    }
    
    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterAiAnalyzer()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    
    public function getUsersQuery()
    {
        // Only show regular users (exclude admins)
        $query = User::where('role', '!=', 'admin');
        
        // Search by name or email
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }
        
        // Filter by premium status
        if ($this->filterPremium === 'premium') {
            $query->where('is_premium', true);
        } elseif ($this->filterPremium === 'free') {
            $query->where('is_premium', false);
        }
        
        // Filter by AI Analyzer usage
        if ($this->filterAiAnalyzer === 'used') {
            $query->where('has_used_ai_analyzer_trial', true);
        } elseif ($this->filterAiAnalyzer === 'not_used') {
            $query->where(function($q) {
                $q->where('has_used_ai_analyzer_trial', false)
                  ->orWhereNull('has_used_ai_analyzer_trial');
            });
        }
        
        return $query;
    }
    
    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        
        $this->editingUserId = $user->id;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editIsPremium = $user->is_premium;
        $this->editIsAdmin = $user->is_admin;
        
        $this->editAiCredits = $user->ai_credits ?? 0;
        $this->editClCredits = $user->cl_credits ?? 0;
        $this->editPhotoCredits = $user->photo_credits ?? 0;
        
        $this->showEditModal = true;
    }
    
    public function updateUser()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editEmail' => 'required|email|max:255',
        ]);
        
        $user = User::findOrFail($this->editingUserId);
        
        $user->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
            'is_premium' => $this->editIsPremium,
            'is_admin' => $this->editIsAdmin,
            'role' => $this->editIsAdmin ? 'admin' : 'user',
        ]);
        
        $this->showEditModal = false;
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'User updated successfully!',
        ]);
    }
    
    public function togglePremium($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_premium = !$user->is_premium;
        $user->payment_status = $user->is_premium ? 'paid' : 'free';
        if ($user->is_premium) {
            $user->premium_purchased_at = now();
        }
        $user->save();
        
        // Refresh quotas based on new status
        $analyzerLimit = \App\Models\Setting::getLimit('ai_analyzer', $user);
        $clLimit = \App\Models\Setting::getLimit('cover_letters', $user);
        $photoLimit = \App\Models\Setting::getLimit('ai_photo', $user);
        
        $user->update([
            'ai_credits' => $analyzerLimit === 'unlimited' ? 9999 : (int) $analyzerLimit,
            'cl_credits' => $clLimit === 'unlimited' ? 9999 : (int) $clLimit,
            'photo_credits' => $photoLimit === 'unlimited' ? 9999 : (int) $photoLimit,
        ]);
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Status Diperbarui',
            'message' => 'Status premium ' . $user->name . ' berhasil diubah.',
        ]);
    }

    public function openPremiumConfirmModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->confirmTargetUserId = $user->id;
        $this->confirmTargetUserName = $user->name;
        $this->confirmTargetUserIsPremium = $user->is_premium;
        $this->showPremiumConfirmModal = true;
    }

    public function closePremiumConfirmModal()
    {
        $this->showPremiumConfirmModal = false;
        $this->reset(['confirmTargetUserId', 'confirmTargetUserName', 'confirmTargetUserIsPremium']);
    }

    public function toggleManualPremium()
    {
        $user = User::findOrFail($this->confirmTargetUserId);
        
        // Toggle is_premium
        $user->is_premium = !$user->is_premium;
        
        // Jika jadi premium, set status ke paid (Manual Override)
        // Jika jadi free, set status ke free
        $user->payment_status = $user->is_premium ? User::PAYMENT_STATUS_PAID : User::PAYMENT_STATUS_FREE;
        
        if ($user->is_premium) {
            $user->premium_purchased_at = now();
        }
        
        $user->save();

        // Refresh quotas based on new status
        $analyzerLimit = \App\Models\Setting::getLimit('ai_analyzer', $user);
        $clLimit = \App\Models\Setting::getLimit('cover_letters', $user);
        $photoLimit = \App\Models\Setting::getLimit('ai_photo', $user);
        
        $user->update([
            'ai_credits' => $analyzerLimit === 'unlimited' ? 9999 : (int) $analyzerLimit,
            'cl_credits' => $clLimit === 'unlimited' ? 9999 : (int) $clLimit,
            'photo_credits' => $photoLimit === 'unlimited' ? 9999 : (int) $photoLimit,
        ]);

        Log::info('Admin toggled manual premium status', [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'new_status' => $user->is_premium ? 'PREMIUM' : 'FREE',
        ]);

        $this->closePremiumConfirmModal();

        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Manual Override Sukses!',
            'message' => 'User ' . $user->name . ' sekarang berstatus ' . ($user->is_premium ? 'PREMIUM' : 'FREE'),
        ]);
    }
    
    public function saveAiQuotas()
    {
        if (!$this->editingUserId) return;
        
        $this->validate([
            'editAiCredits' => 'required|numeric|min:0',
            'editClCredits' => 'required|numeric|min:0',
            'editPhotoCredits' => 'required|numeric|min:0',
        ]);
        
        $user = User::findOrFail($this->editingUserId);
        $user->update([
            'ai_credits' => (int) $this->editAiCredits,
            'cl_credits' => (int) $this->editClCredits,
            'photo_credits' => (int) $this->editPhotoCredits,
        ]);
        
        Log::info('Admin manually updated AI quotas', [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
            'ai_credits' => $this->editAiCredits,
            'cl_credits' => $this->editClCredits,
            'photo_credits' => $this->editPhotoCredits,
        ]);
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Kuota Diperbarui',
            'message' => 'Kuota AI berhasil diubah secara manual.',
        ]);
    }
    
    public function resetAllAiQuotas()
    {
        if (!$this->editingUserId) return;
        
        $user = User::findOrFail($this->editingUserId);
        
        $analyzerLimit = \App\Models\Setting::getLimit('ai_analyzer', $user);
        $clLimit = \App\Models\Setting::getLimit('cover_letters', $user);
        $photoLimit = \App\Models\Setting::getLimit('ai_photo', $user);
        
        $user->update([
            'ai_credits' => $analyzerLimit === 'unlimited' ? 9999 : (int) $analyzerLimit,
            'ai_analyzer_count_this_month' => 0,
            'has_used_ai_analyzer_trial' => false,
            'last_ai_analyzer_reset' => now(),
            
            'cl_credits' => $clLimit === 'unlimited' ? 9999 : (int) $clLimit,
            'photo_credits' => $photoLimit === 'unlimited' ? 9999 : (int) $photoLimit,
        ]);
        
        $this->editAiCredits = $user->ai_credits;
        $this->editClCredits = $user->cl_credits;
        $this->editPhotoCredits = $user->photo_credits;
        
        Log::info('Admin reset all AI quotas for user', [
            'admin_id' => Auth::id(),
            'user_id' => $user->id,
        ]);
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Quota Di-reset',
            'message' => 'Semua Kuota AI untuk ' . $user->name . ' telah dikembalikan ke standar.',
        ]);
    }
    
    public function toggleAdmin($userId)
    {
        $user = User::findOrFail($userId);
        $newIsAdmin = !$user->is_admin;
        
        $user->update([
            'is_admin' => $newIsAdmin,
            'role' => $newIsAdmin ? 'admin' : 'user',
        ]);
        
        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Updated',
            'message' => 'Admin status toggled!',
        ]);
    }
    
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent deleting self
        if ($user->id === Auth::id()) {
            $this->dispatch('showNotification', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'You cannot delete yourself!',
            ]);
            return;
        }
        
        $user->delete();
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Deleted',
            'message' => 'User deleted successfully!',
        ]);
    }
    
    public function confirmDeleteUserModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->deleteTargetUserId = $user->id;
        $this->deleteTargetUserName = $user->name;
        $this->showDeleteConfirmModal = true;
    }
    
    public function closeDeleteConfirmModal()
    {
        $this->showDeleteConfirmModal = false;
        $this->reset(['deleteTargetUserId', 'deleteTargetUserName']);
    }
    
    public function performDelete()
    {
        $this->deleteUser($this->deleteTargetUserId);
        $this->closeDeleteConfirmModal();
    }
    
    public function closeModal()
    {
        $this->showEditModal = false;
        $this->reset(['editingUserId', 'editName', 'editEmail', 'editIsPremium', 'editIsAdmin']);
    }
    
    public function openCreateAdminModal()
    {
        $this->showCreateAdminModal = true;
    }
    
    public function closeCreateAdminModal()
    {
        $this->showCreateAdminModal = false;
        $this->reset(['newAdminName', 'newAdminEmail', 'newAdminPassword', 'newAdminPasswordConfirmation']);
    }
    
    public function createAdmin()
    {
        $this->validate([
            'newAdminName' => 'required|string|max:255',
            'newAdminEmail' => 'required|email|max:255|unique:users,email',
            'newAdminPassword' => 'required|min:8|confirmed',
        ], [
            'newAdminName.required' => 'Nama harus diisi',
            'newAdminEmail.required' => 'Email harus diisi',
            'newAdminEmail.email' => 'Format email tidak valid',
            'newAdminEmail.unique' => 'Email sudah terdaftar',
            'newAdminPassword.required' => 'Password harus diisi',
            'newAdminPassword.min' => 'Password minimal 8 karakter',
            'newAdminPassword.confirmed' => 'Konfirmasi password tidak cocok',
        ]);
        
        User::create([
            'name' => $this->newAdminName,
            'email' => $this->newAdminEmail,
            'password' => bcrypt($this->newAdminPassword),
            'role' => 'admin',
            'is_admin' => true,
            'is_premium' => true,
            'payment_status' => 'paid',
            'email_verified_at' => now(),
        ]);
        
        $this->closeCreateAdminModal();
        
        $this->dispatch('showNotification', [
            'type' => 'success',
            'title' => 'Admin Created!',
            'message' => 'Admin user created successfully!',
        ]);
    }
    
    public function openSendEmailModal($userId)
    {
        $user = User::findOrFail($userId);
        
        $this->emailTargetUserId = $user->id;
        $this->emailTargetUserName = $user->name;
        $this->emailTargetUserEmail = $user->email;
        $this->emailType = 'welcome';
        
        $this->showSendEmailModal = true;
    }
    
    public function closeSendEmailModal()
    {
        $this->showSendEmailModal = false;
        $this->reset(['emailTargetUserId', 'emailTargetUserName', 'emailTargetUserEmail', 'emailType']);
    }
    
    public function sendEmail()
    {
        $this->validate([
            'emailType' => 'required|in:welcome,verification,verification_reminder,ai_analyzer,job_reminder,monthly_motivation,premium_granted,product_update,hiring_season,re_engagement,ai_photo,follow_up_feature,idul_adha,waisak,pancasila,sumpah_pemuda,pahlawan,guru_nasional,hari_ibu,natal,kemerdekaan_ri,maulid_nabi,tahun_baru_islam',
        ]);
        
        $user = User::findOrFail($this->emailTargetUserId);
        
        try {
            switch ($this->emailType) {
                case 'welcome':
                    Mail::to($user->email)->send(new WelcomeMail($user));
                    break;
                case 'verification':
                    if (!$user->hasVerifiedEmail()) {
                        $user->sendEmailVerificationNotification();
                    } else {
                        $this->dispatch('showNotification', [
                            'type' => 'warning',
                            'title' => 'Email Sudah Terverifikasi',
                            'message' => 'User ini sudah memiliki email terverifikasi.',
                        ]);
                        return;
                    }
                    break;
                case 'verification_reminder':
                    Mail::to($user->email)->send(new EmailVerificationReminderMail($user));
                    break;
                case 'ai_analyzer':
                    Mail::to($user->email)->send(new AiAnalyzerFreeTrialAnnouncementMail($user));
                    break;
                case 'job_reminder':
                    Mail::to($user->email)->send(new JobApplicationReminderMail($user));
                    break;
                case 'monthly_motivation':
                    Mail::to($user->email)->send(new MonthlyMotivationMail($user));
                    break;
                case 'premium_granted':
                    Mail::to($user->email)->send(new PremiumGrantedMail($user));
                    break;
                case 'product_update':
                    Mail::to($user->email)->send(new \App\Mail\ProductUpdateMail($user));
                    break;
                case 'hiring_season':
                    Mail::to($user->email)->send(new HiringSeasonAlertMail($user));
                    break;
                case 're_engagement':
                    Mail::to($user->email)->send(new ReEngagementMail($user));
                    break;
                case 'ai_photo':
                    Mail::to($user->email)->send(new \App\Mail\AiPhotoAnnouncementMail($user));
                    break;
                case 'follow_up_feature':
                    Mail::to($user->email)->send(new FollowUpFeatureAnnouncementMail($user));
                    break;
                case 'idul_adha':
                    Mail::to($user->email)->send(new \App\Mail\IdulAdhaMail($user));
                    break;
                case 'waisak':
                    Mail::to($user->email)->send(new \App\Mail\WaisakMail($user));
                    break;
                case 'pancasila':
                    Mail::to($user->email)->send(new \App\Mail\PancasilaMail($user));
                    break;
                case 'sumpah_pemuda':
                    Mail::to($user->email)->send(new \App\Mail\SumpahPemudaMail($user));
                    break;
                case 'pahlawan':
                    Mail::to($user->email)->send(new \App\Mail\PahlawanMail($user));
                    break;
                case 'guru_nasional':
                    Mail::to($user->email)->send(new \App\Mail\GuruNasionalMail($user));
                    break;
                case 'hari_ibu':
                    Mail::to($user->email)->send(new \App\Mail\HariIbuMail($user));
                    break;
                case 'natal':
                    Mail::to($user->email)->send(new \App\Mail\NatalMail($user));
                    break;
                case 'kemerdekaan_ri':
                    Mail::to($user->email)->send(new \App\Mail\KemerdekaanRiMail($user));
                    break;
                case 'maulid_nabi':
                    Mail::to($user->email)->send(new \App\Mail\MaulidNabiMail($user));
                    break;
                case 'tahun_baru_islam':
                    Mail::to($user->email)->send(new \App\Mail\TahunBaruIslamMail($user));
                    break;
            }
            
            Log::info('Admin sent email to user', [
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'email_type' => $this->emailType,
            ]);
            
            $this->closeSendEmailModal();
            
            $emailTypeNames = [
                'welcome'                 => 'Welcome Email',
                'verification'            => 'Verification Email',
                'verification_reminder'   => 'Verification Reminder',
                'ai_analyzer'             => 'AI Analyzer Announcement',
                'job_reminder'            => 'Job Application Reminder',
                'monthly_motivation'      => 'Monthly Motivation',
                'premium_granted'         => 'Premium Granted Notification',
                'product_update'          => 'Major Product Update',
                'hiring_season'           => 'Hiring Season Alert',
                're_engagement' => 'Re-engagement',
                'ai_photo' => 'AI Photo Announcement',
                'follow_up_feature' => 'Follow Up Feature',
                'idul_adha' => 'Idul Adha',
                'waisak' => 'Hari Raya Waisak',
                'pancasila' => 'Hari Lahir Pancasila',
                'sumpah_pemuda' => 'Hari Sumpah Pemuda',
                'pahlawan' => 'Hari Pahlawan',
                'guru_nasional' => 'Hari Guru Nasional',
                'hari_ibu' => 'Hari Ibu',
                'natal' => 'Hari Raya Natal',
                'kemerdekaan_ri' => 'Hari Kemerdekaan RI',
                'maulid_nabi' => 'Maulid Nabi Muhammad SAW',
                'tahun_baru_islam' => 'Tahun Baru Islam',
            ];
            
            $this->dispatch('showNotification', [
                'type' => 'success',
                'title' => 'Email Terkirim!',
                'message' => $emailTypeNames[$this->emailType] . ' berhasil dikirim ke ' . $user->email,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send email to user', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'email_type' => $this->emailType,
                'error' => $e->getMessage(),
            ]);
            
            $this->dispatch('showNotification', [
                'type' => 'error',
                'title' => 'Gagal Mengirim Email',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
    
    public function render()
    {
        $effectivePerPage = ($this->perPage === 'all') ? 1000000 : (int) $this->perPage;
        if ($effectivePerPage <= 0) {
            $effectivePerPage = 10;
        }

        $users = $this->getUsersQuery()
            ->latest()
            ->paginate($effectivePerPage);
        
        // Stats only for regular users (exclude admins)
        $stats = [
            'total' => User::where('role', '!=', 'admin')->count(),
            'premium' => User::where('role', '!=', 'admin')->where('is_premium', true)->count(),
            'free' => User::where('role', '!=', 'admin')->where('is_premium', false)->count(),
            // Verified = users (non-admin) with verified email
            'verified' => User::where('role', '!=', 'admin')
                ->whereNotNull('email_verified_at')
                ->count(),
            // AI Analyzer free trial users
            'ai_analyzer_trial_used' => User::where('role', '!=', 'admin')
                ->where('has_used_ai_analyzer_trial', true)
                ->count(),
        ];
        
        return view('livewire.admin.user-management', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }

    public function exportUsers()
    {
        $users = $this->getUsersQuery()->latest()->get();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID', 
            'Nama', 
            'Email', 
            'Status Premium', 
            'AI Trial', 
            'Role', 
            'Tanggal Daftar', 
            'Terverifikasi'
        ];

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for proper UTF-8 Excel support
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $row = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->is_premium ? 'Premium' : 'Free',
                    $user->has_used_ai_analyzer_trial ? 'Sudah' : 'Belum',
                    $user->is_admin ? 'Admin' : 'User',
                    $user->created_at ? $user->created_at->format('d M Y H:i') : '-',
                    $user->email_verified_at ? 'Ya' : 'Tidak'
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
