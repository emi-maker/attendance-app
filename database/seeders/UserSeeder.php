<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        
        //ユーザー
        User::create([
            'name' => '立山 奈々子',
            'email' => 'test@test.com',
            'password' => Hash::make('12345678'),
    ]);

        User::create([
            'name' => '西山 達也',
            'email' => 'test@example.org',
            'password' => Hash::make('aaa11111'),
    ]);
    }
}
