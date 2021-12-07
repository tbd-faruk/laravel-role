<?php

namespace Technobd\Permission\Middlewares;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->hasRole('super-admin')){
            return $next($request);
        }
        if(!auth()->user() && !$request->url()){
            abort(403, 'Unauthorized action.');
        }
        if(!auth()->user()->hasPermissionByPath($request->url())){
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
