<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Pegawai;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Login admin
        $admin = Admin::where('username', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            session(['role' => 'admin']);
            return redirect()->route('admin.dashboard');
        }

        // Login pegawai
        $pegawai = Pegawai::where('pegawai_id', $request->email)->first();
        if ($pegawai && Hash::check($request->password, $pegawai->password)) {
            Auth::guard('pegawai')->login($pegawai);
            auth()->setDefaultDriver('pegawai');
            session(['role' => 'pegawai']);
            return redirect()->route('pegawai.dashboard');
        }

        return back()->withErrors(['email' => 'ID atau Password salah.']);
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (session('role') === 'admin') {
            Auth::guard('admin')->logout();
        } elseif (session('role') === 'pegawai') {
            Auth::guard('pegawai')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}