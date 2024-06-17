<?php

namespace Database\Seeders\User;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => "Admin",
            'email' => "admin@gmail.com",
            'password' => Hash::make(".£oU68ytEm22Vz2"),
        ]);

        $user->assignRole('Admin');
    }
}
