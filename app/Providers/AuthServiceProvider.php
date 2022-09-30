<?php

namespace App\Providers;

use Gate;
use App\Models\User;
use App\Policies\BreakModelPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-break-requests', [BreakModelPolicy::class, 'viewRequests']);
        Gate::define('create-break-request', [BreakModelPolicy::class, 'create']);
        Gate::define('view-breaks', [BreakModelPolicy::class, 'viewBreaks']);
    }
}
