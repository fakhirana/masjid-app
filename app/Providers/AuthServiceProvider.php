<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Registration;
use App\Policies\RegistrationPolicy;
use App\Policies\DashboardPolicy;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => DashboardPolicy::class,

        Registration::class => RegistrationPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
