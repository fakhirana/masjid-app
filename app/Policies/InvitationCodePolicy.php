<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InvitationCode;

class InvitationCodePolicy
{
    /**
     * Pengurus boleh generate invitation code
     */
    public function generate(User $user): bool
    {
        return $user->role === 'pengurus';
    }

    /**
     * Warga boleh menggunakan invitation code
     */
    public function use(User $user, InvitationCode $invitationCode): bool
    {
        return $user->role === 'warga'
            && $invitationCode->used_at === null;
    }
}
