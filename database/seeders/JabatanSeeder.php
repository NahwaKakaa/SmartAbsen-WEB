<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        Jabatan::create(['nama_jabatan' => 'Staff IT']);
        Jabatan::create(['nama_jabatan' => 'HRD']);
        Jabatan::create(['nama_jabatan' => 'Manager']);
    }
}
