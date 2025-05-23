<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;

class ProfilController extends Controller
{
    public function edit()
    {
       /** @var \App\Models\Pegawai $pegawai */
        $pegawai = Auth::user();
        return view('pegawai.profil.edit', compact('pegawai'));
    }

    public function update(Request $request)
    {
       /** @var \App\Models\Pegawai $pegawai */
        $pegawai = Auth::user();

        $request->validate([
            'nama' => 'required',
            'email' => 'nullable|email|unique:pegawais,email,' . $pegawai->id,
            'alamat' => 'nullable|string',
        ]);

        $pegawai->update($request->only(['nama', 'email', 'alamat']));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

       /** @var \App\Models\Pegawai $pegawai */
        $pegawai = Auth::user();

        if (!Hash::check($request->password_lama, $pegawai->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        $pegawai->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
