<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check() || session('role') !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }
        return $next($request);
    }
}
