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
                'status' => 'approved',
                'division_id' => 1,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP000000001',
                'name' => 'Direksi User',
                'email' => 'direksi@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'direksi',
                'status' => 'approved',
                'division_id' => 1,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP000000002',
                'name' => 'HRD User',
                'email' => 'hrd@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'hrd',
                'status' => 'approved',
                'division_id' => 2,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP000000003',
                'name' => 'staff User',
                'email' => 'staff@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => 'approved',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP000000004',
                'name' => 'staff User 2',
                'email' => 'staff2@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => 'approved',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP000000005',
                'name' => 'kasie User',
                'email' => 'kasie@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'kasie',
                'status' => 'approved',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nik' => 'AP000000006',
                'name' => 'kabag User',
                'email' => 'kabag@cutiapp.com',
                'password' => Hash::make('password'),
                'role' => 'kabag',
                'status' => 'approved',
                'division_id' => 3,
                'sisa_cuti' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
