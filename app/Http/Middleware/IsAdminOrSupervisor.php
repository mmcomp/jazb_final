<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class IsAdminOrSupervisor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->groups_id && (Auth::user()->group->type == 'admin' || Auth::user()->group->type == 'supervisor')){
            return $next($request);
        }
    }
}
