<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'admin',
            'last_name' => 'DiaKawan',
            'username' => 'admin',
            'email' => 'bM2vH@example.com',
            'password' => Hash::make('12345678'),
            'phone' => '0812345678',
            'age' => '20',
            'address' => 'Jl. Test',
        ]);

        User::factory()->count(10)->create();
    }
}
