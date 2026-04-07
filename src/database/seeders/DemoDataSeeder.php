<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->admin()->create();

        $users = User::factory()->count(5)->create();

        foreach ($users as $user) {
            for ($i = 0; $i < 10; $i++) {
                $attendance = Attendance::factory()->create([
                    'user_id' => $user->id,
                    'work_date' => now()->subDays($i)->format('Y-m-d'),
                ]);

                $breakCount = rand(1, 3);

                for ($j = 0; $j < $breakCount; $j++) {
                    $breakStart = Carbon::parse($attendance->clock_in)
                        ->copy()
                        ->addHours(3 + $j)
                        ->setMinute(0);

                    $breakEnd = $breakStart->copy()->addMinutes(30);

                    BreakTime::create([
                        'attendance_id' => $attendance->id,
                        'break_start' => $breakStart,
                        'break_end' => $breakEnd,
                    ]);
                }
            }
        }
    }
}
