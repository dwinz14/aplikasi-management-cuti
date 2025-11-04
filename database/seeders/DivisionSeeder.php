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
            ['nama_divisi' => 'operasional', 'created_at' => now(), 'updated_at' => now()],
            ['nama_divisi' => 'skai', 'created_at' => now(), 'updated_at' => now()],
            ['nama_divisi' => 'apuppt', 'created_at' => now(), 'updated_at' => now()],
            ['nama_divisi' => 'hrd', 'created_at' => now(), 'updated_at' => now()],
            ['nama_divisi' => 'umum', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
