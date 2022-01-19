<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('role','=','admin')->first();
        $user = User::where('role','=','user')->first();
        if ($admin == "") {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
            ]);
        }

        if ($user == "") {
            User::create([
                'name' => 'Matthew',
                'email' => 'matthew@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'user',
            ]);
        }
    }
}
