<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function reply(User $user, SupportTicket $ticket): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, SupportTicket $ticket): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, SupportTicket $ticket): bool
    {
        return $user->isAdmin();
    }
}
