<?php

namespace App\Providers;

use App\Models\Empresa;
use App\Policies\EmpresaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Task;
use App\Models\TaskTemplate;
use App\Policies\TaskPolicy;
use App\Policies\TaskTemplatePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Empresa::class => EmpresaPolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Task::class => TaskPolicy::class,
        TaskTemplate::class => TaskTemplatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
