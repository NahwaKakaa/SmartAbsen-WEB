<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'pegawai_id', 'nama', 'email', 'jenis_kelamin',
        'alamat', 'jabatan_id', 'foto', 'password'
    ];

    protected $hidden = ['password'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}

