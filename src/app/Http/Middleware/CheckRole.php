<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    
    public function handle($request, Closure $next)
    {
    
        if (!auth()->check() && !Auth::guard('admin')->check()) {

        // URLで判断（adminかどうか）
        if ($request->is('admin/*')) {
            return redirect('/admin/login');
        }

        return redirect('/login');
    }

    if (Auth::guard('admin')->check()) {
        session(['role' => 'admin']);
    } else {
        session(['role' => 'user']);
    }

    return $next($request);
    }   
}