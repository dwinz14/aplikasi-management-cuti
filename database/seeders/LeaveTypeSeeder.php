<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_types')->insert([
            ['nama_jenis_cuti' => 'Cuti Tahunan', 'jumlah_hari' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_cuti' => 'Cuti lebaran', 'jumlah_hari' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
