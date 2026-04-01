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
        //管理者
        User::create([
            'name' => '管理者',
            'email' => 'admin@test.com',
            'password' => Hash::make('12345678'),
            'is_admin' => 1,
    ]);

        //ユーザー
        User::create([
            'name' => '立山奈々子',
            'email' => 'test@test.com',
            'password' => Hash::make('12345678'),
            'is_admin' => 0,
    ]);
    }
}
