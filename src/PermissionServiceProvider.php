<?php

namespace Technobd\Permission;

use Technobd\Permission\Middleware\RoleMiddleware;
use Technobd\Permission\Middleware\PermissionMiddleware;
use Technobd\Permission\Models\Permission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('Role', RoleMiddleware::class);
        $router->pushMiddlewareToGroup('permission', PermissionMiddleware::class);

        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {

            report($e);
            return false;
        }

        //Blade directives
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})) { ?>"; //return this if statement inside php tag
        });

        Blade::directive('endrole', function ($role) {
            return "<?php } ?>"; //return this endif statement inside php tag
        });

    }
}
