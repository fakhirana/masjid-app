<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QurbanOwner;

class QurbanOwnerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'pengurus';
    }

    public function view(User $user, QurbanOwner $owner): bool
    {
        return $user->role === 'pengurus';
    }

    public function create(User $user): bool
    {
        return $user->role === 'pengurus';
    }

    public function update(User $user, QurbanOwner $owner): bool
    {
        return $user->role === 'pengurus';
    }

    public function delete(User $user, QurbanOwner $owner): bool
    {
        return $user->role === 'pengurus';
    }
}
