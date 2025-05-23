<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawais = Pegawai::with('jabatan')->get();
        return view('admin.pegawai.index', compact('pegawais'));
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        return view('admin.pegawai.create', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|unique:pegawais',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'jabatan_id' => 'required',
        ]);

        Pegawai::create([
            'pegawai_id' => $request->pegawai_id,
            'nama' => $request->nama,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jabatan_id' => $request->jabatan_id,
            'password' => Hash::make('default123'), // password default
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $jabatans = Jabatan::all();
        return view('admin.pegawai.edit', compact('pegawai', 'jabatans'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'jabatan_id' => 'required',
        ]);

        $pegawai->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jabatan_id' => $request->jabatan_id,
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai diperbarui!');
    }

    public function destroy($id)
    {
        Pegawai::destroy($id);
        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai dihapus!');
    }

    public function resetPassword($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update(['password' => Hash::make('default123')]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Password berhasil direset ke default.');
    }
}