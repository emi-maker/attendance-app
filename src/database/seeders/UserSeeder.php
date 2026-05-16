<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;
use App\Models\BreakTime;

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

        Attendance::create([
            'user_id' => 1,
            'work_date' => '2026-05-01',
            'clock_in' => '2026-05-01 09:00:00',
            'clock_out' => '2026-05-01 18:00:00',
    ]);

        Attendance::create([
            'user_id' => 2,
            'work_date' => '2026-05-01',
            'clock_in' => '2026-05-02 10:00:00',
            'clock_out' => '2026-05-02 19:00:00',
    ]);
        BreakTime::create([
            'attendance_id' => 1,
            'break_start' => '2026-05-01 12:00:00',
            'break_end' => '2026-05-01 13:00:00',
    ]);

        BreakTime::create([
            'attendance_id' => 2,
            'break_start' => '2026-05-01 13:00:00',
            'break_end' => '2026-05-01 14:00:00',
    ]);
    }
}
