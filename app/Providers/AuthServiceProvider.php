<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// Models
use App\Models\User;
use App\Models\Attendance;
use App\Models\Registration;
use App\Models\WargaVerification;
use App\Models\InvitationCode;

// Policies
use App\Policies\DashboardPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\InvitationCodePolicy;
use App\Policies\RegistrationPolicy;
use App\Policies\WargaVerificationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Dashboard
        User::class => DashboardPolicy::class,

        // Event Attendance
        Attendance::class => AttendancePolicy::class,

        // Warga Verification
        WargaVerification::class => WargaVerificationPolicy::class,

        // Invitation Code
        InvitationCode::class => InvitationCodePolicy::class,

        // (jika masih dipakai)
        // Registration::class => RegistrationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
