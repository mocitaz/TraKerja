<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterPremium = 'all'; // all, premium, free
    public $filterRole = 'all'; // all, admin, user
    public $perPage = 10; // supports 10, 20, 50, 100, 'all'
    
    public $showEditModal = false;
    public $showCreateAdminModal = false;
    public $editingUserId;
    public $editName;
    public $editEmail;
    public $editIsPremium;
    public $editIsAdmin;
    
    // Create Admin properties
    public $newAdminName;
    public $newAdminEmail;
    public $newAdminPassword;
    public $newAdminPasswordConfirmation;
    
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
        $user->update([
            'is_premium' => !$user->is_premium,
            'payment_status' => $user->is_premium ? 'unpaid' : 'paid',
        ]);
        
        $this->dispatch('showNotification', [
            'type' => 'info',
            'title' => 'Updated',
            'message' => 'Premium status toggled!',
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
        ];
        
        return view('livewire.admin.user-management', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }
}
