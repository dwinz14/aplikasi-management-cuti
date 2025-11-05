<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\LeaveType::create([
            'name' => 'Cuti Tahunan',
            'quota' => 12,
            'gender' => null,
            'is_active' => true,
        ]);

        \App\Models\LeaveType::create([
            'name' => 'Cuti Sakit',
            'quota' => 0, // tanpa batas
            'gender' => null,
            'is_active' => true,
        ]);

        \App\Models\LeaveType::create([
            'name' => 'Cuti Melahirkan',
            'quota' => 90,
            'gender' => 'P', // hanya untuk perempuan
            'is_active' => true,
        ]);
    }
}
