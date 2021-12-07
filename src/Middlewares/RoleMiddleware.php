<?php

namespace Technobd\Permission\Middlewares;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role=null, $permission = null)
    {
        if($permission !== null && !$request->user()->hasRole($role)) {
            return response()->view('error.roleerror');
        }
        if($permission !== null && !$request->user()->can($permission)) {
            return response()->view('error.roleerror');
        }
        return $next($request);

    }
}
