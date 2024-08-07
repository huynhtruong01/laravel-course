<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Truong',
            'email' => 'htruong@gmail.com',
            'gender' => 'Male',
            'email_verified_at' => date("Y/m/d"),
            'password' => '12345678',
            'remember_token' => 'abc',
        ]);
    }
}
