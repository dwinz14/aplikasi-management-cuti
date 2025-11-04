<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            ['nama_kantor' => 'pusat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kantor' => 'pare', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kantor' => 'kediri', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
