<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nik' => '1',
                'name' => 'Super Admin',
                'email' => 'admin@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'division_id' => 1,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP001',
                'name' => 'Direksi User',
                'email' => 'direksi@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'direksi',
                'division_id' => 1,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP002',
                'name' => 'HRD User',
                'email' => 'hrd@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'hrd',
                'division_id' => 2,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP003',
                'name' => 'staff User',
                'email' => 'staff@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP004',
                'name' => 'staff User 2',
                'email' => 'staff2@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP005',
                'name' => 'kasie User',
                'email' => 'kasie@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'kasie',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP006',
                'name' => 'kadiv User',
                'email' => 'kadiv@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'kadiv',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
