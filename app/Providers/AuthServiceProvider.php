<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Tag' => 'App\Policies\TagPolicy',
        'App\Client' => 'App\Policies\ClientPolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\Role' => 'App\Policies\UserPolicy',
        'App\Project' => 'App\Policies\ProjectPolicy',
        'App\TimeEntry' => 'App\Policies\TrackerPolicy',
        'App\Estimate' => 'App\Policies\EstimatePolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        $gate->define('add-tag', 'App\Policies\TagPolicy@add');
        $gate->define('delete-tag', 'App\Policies\TagPolicy@delete');
    }
}
