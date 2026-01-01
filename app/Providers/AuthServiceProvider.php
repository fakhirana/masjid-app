<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Registration;
use App\Policies\RegistrationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Registration::class => RegistrationPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
