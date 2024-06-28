<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'adminpusat',
            ],
            [
                'name' => 'Mitra',
                'email' => 'mitra@example.com',
                'password' => Hash::make('password'),
                'role' => 'mitra',
            ],
            [
                'name' => 'pengunjung',
                'email' => 'pengunjung@example.com',
                'password' => Hash::make('password'),
                'role' => 'pengunjung',
            ],
        ]);
    }
}
