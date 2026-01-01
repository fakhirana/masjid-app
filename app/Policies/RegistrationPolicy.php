<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use App\Models\Registration;

class RegistrationPolicy
{
    /**
     * Konfirmasi kehadiran event
     */
    public function create(User $user, Event $event): bool
    {
        return in_array($user->role, ['warga', 'pengurus'])
            && $event->status === 'active';
    }

    /**
     * Melihat daftar konfirmasi (pengurus)
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'pengurus';
    }

    /**
     * Menghapus konfirmasi
     */
    public function delete(User $user, Registration $registration): bool
    {
        if ($user->role === 'pengurus') {
            return true;
        }

        return $user->role === 'warga'
            && $registration->user_id === $user->id;
    }
}
