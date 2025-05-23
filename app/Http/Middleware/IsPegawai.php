<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IsPegawai
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pegawai')->check() || session('role') !== 'pegawai') {
            return redirect()->route('login')->with('error', 'Akses ditolak.');
        }

        return $next($request);
    }
}

