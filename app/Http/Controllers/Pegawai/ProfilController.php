<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProfilController extends Controller
{
    public function edit()
    {
       /** @var \App\Models\Pegawai $pegawai */
       $pegawai = Auth::guard('pegawai')->user();
       return view('pegawai.profil.edit', compact('pegawai'));
    }

    public function update(Request $request)
    {
       /** @var \App\Models\Pegawai $pegawai */
       $pegawai = Auth::guard('pegawai')->user();

       $request->validate([
           'nama' => 'required|string|max:255',
           'email' => 'nullable|email|max:255|unique:pegawais,email,' . $pegawai->id,
           'alamat' => 'nullable|string|max:500',
       ]);

       $pegawai->update($request->only(['nama', 'email', 'alamat']));

       return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        Log::info('Mencoba update password untuk pegawai ID: ' . Auth::guard('pegawai')->id());

        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        $pegawai = Auth::guard('pegawai')->user();

        Log::info('Password lama dari request: ' . $request->password_lama);
        Log::info('Hash password di database: ' . $pegawai->password);

        if (!Hash::check($request->password_lama, $pegawai->password)) {
            Log::warning('Verifikasi password lama gagal untuk pegawai ID: ' . $pegawai->id);
            throw ValidationException::withMessages([
                'password_lama' => 'Password lama salah.',
            ]);
        }

        Log::info('Verifikasi password lama berhasil. Memperbarui password baru...');
        Log::info('Password baru dari request (plain text): ' . $request->password_baru);

        try {
            $pegawai->update([
                'password' => $request->password_baru,
            ]);

            Log::info('Password berhasil diperbarui di database untuk pegawai ID: ' . $pegawai->id);
            return back()->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui password untuk pegawai ID ' . $pegawai->id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui password. Silakan coba lagi.');
        }
    }
}
