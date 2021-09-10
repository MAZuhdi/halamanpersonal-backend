<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'HalamanPersonal Admin' ,
            'username' => 'admin',
            'email' => 'halamanpersonal@gmail.com',
            'password' => Hash::make('password'),
            'theme_id' => 1
        ]);
    }
}
