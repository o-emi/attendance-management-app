<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;
use App\Models\CorrectionRequestBreakTime;
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
        $admin = User::factory()->admin()->create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $users = collect([
            User::factory()->create(['name' => 'ユーザー1', 'email' => 'user1@example.com', 'password' => bcrypt('password')]),
            User::factory()->create(['name' => 'ユーザー2', 'email' => 'user2@example.com', 'password' => bcrypt('password')]),
            User::factory()->create(['name' => 'ユーザー3', 'email' => 'user3@example.com', 'password' => bcrypt('password')]),
            User::factory()->create(['name' => 'ユーザー4', 'email' => 'user4@example.com', 'password' => bcrypt('password')]),
            User::factory()->create(['name' => 'ユーザー5', 'email' => 'user5@example.com', 'password' => bcrypt('password')]),
        ]);

        foreach ($users as $user) {

            for ($i = 30; $i >= 1; $i--) {
                $workDate = now()->subDays($i)->format('Y-m-d');

                $attendance = Attendance::factory()->create([
                    'user_id' => $user->id,
                    'work_date' => $workDate,
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

                if (rand(0, 1)) {
                    $correctionRequest = CorrectionRequest::create([
                        'user_id' => $user->id,
                        'attendance_id' => $attendance->id,
                        'start_time' => Carbon::parse($attendance->clock_in)->subMinutes(15)->format('H:i:s'),
                        'end_time' => Carbon::parse($attendance->clock_out)->addMinutes(15)->format('H:i:s'),
                        'note' => '打刻漏れのため修正申請',
                        'status' => rand(0, 1) ? 'pending' : 'approved',
                        'break_times' => [],
                    ]);

                    $requestBreakCount = rand(1, 2);
                    for ($k = 0; $k < $requestBreakCount; $k++) {
                        $requestBreakStart = Carbon::parse($attendance->clock_in)
                            ->copy()
                            ->addHours(3 + $k)
                            ->setMinute(0)
                            ->format('H:i:s');
                        $requestBreakEnd = Carbon::parse($requestBreakStart)
                            ->copy()
                            ->addMinutes(45)
                            ->format('H:i:s');

                        CorrectionRequestBreakTime::create([
                            'correction_request_id' => $correctionRequest->id,
                            'break_start' => $requestBreakStart,
                            'break_end' => $requestBreakEnd,
                        ]);
                    }
                }
            }

            $todayAttendance = Attendance::firstOrCreate(
                ['user_id' => $user->id, 'work_date' => now()->format('Y-m-d')],
                ['clock_in' => null, 'clock_out' => null, 'status' => 'off']
            );
        }
    }
}