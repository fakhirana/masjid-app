<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WargaVerification;

class WargaVerificationPolicy
{
    /**
     * TAMU boleh mengajukan verifikasi warga
     */
    public function create(User $user): bool
    {
        return $user->role === 'tamu';
    }

    /**
     * Pengurus melihat semua pengajuan
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'pengurus';
    }

    /**
     * Pengurus menyetujui / menolak
     */
    public function approve(User $user, WargaVerification $verification): bool
    {
        return $user->role === 'pengurus';
    }
}
