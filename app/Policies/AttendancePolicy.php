<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use App\Models\Attendance;

class AttendancePolicy
{
    /**
     * Konfirmasi kehadiran event
     * - Role: warga & pengurus
     * - Event harus aktif
     */
    public function create(User $user, Event $event): bool
    {
        return in_array($user->role, ['warga', 'pengurus'])
            && $event->status === 'active';
    }

    /**
     * Melihat daftar kehadiran (KHUSUS pengurus)
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'pengurus';
    }

    /**
     * Melihat detail kehadiran
     * - Pengurus: bebas
     * - Warga: hanya miliknya sendiri
     */
    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->role === 'pengurus') {
            return true;
        }

        return $user->role === 'warga'
            && $attendance->user_id === $user->id;
    }

    /**
     * Menghapus kehadiran
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        if ($user->role === 'pengurus') {
            return true;
        }

        return $user->role === 'warga'
            && $attendance->user_id === $user->id;
    }
}
