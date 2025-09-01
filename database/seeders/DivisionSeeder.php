<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->insert([
            ['nama_divisi' => 'direksi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_divisi' => 'HRD', 'created_at' => now(), 'updated_at' => now()],
            ['nama_divisi' => 'operasional', 'created_at' => now(), 'updated_at' => now()],
            ['nama_divisi' => 'Marketing', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
