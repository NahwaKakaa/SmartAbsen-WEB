<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'pegawai_id', 'tanggal', 'jam_datang', 'jam_pulang',
        'foto_datang', 'foto_pulang', 'kegiatan', 'status'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}

