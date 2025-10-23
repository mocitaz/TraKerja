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
    public $perPage = 10;
    
    public $showEditModal = false;
    public $editingUserId;
    public $editName;
    public $editEmail;
    public $editIsPremium;
    public $editIsAdmin;
    
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
    
    public function getUsersQuery()
    {
        $query = User::query();
        
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
        
        // Filter by role
        if ($this->filterRole === 'admin') {
            $query->where('is_admin', true);
        } elseif ($this->filterRole === 'user') {
            $query->where('is_admin', false);
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
        $user->update([
            'is_admin' => !$user->is_admin,
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
    
    public function render()
    {
        $users = $this->getUsersQuery()
            ->latest()
            ->paginate($this->perPage);
        
        $stats = [
            'total' => User::count(),
            'premium' => User::where('is_premium', true)->count(),
            'free' => User::where('is_premium', false)->count(),
            'admins' => User::where('is_admin', true)->count(),
        ];
        
        return view('livewire.admin.user-management', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }
}
