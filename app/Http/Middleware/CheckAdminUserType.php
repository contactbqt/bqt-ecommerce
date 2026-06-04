<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminUserType
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors('Access denied');
        }
        return $next($request);
    }
}
