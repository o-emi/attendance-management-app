<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $workDate = $this->faker->dateTimeBetween('-1 month', 'now');

        $clockIn = Carbon::instance($workDate)
            ->setTime(rand(7, 9), rand(0, 1) ? 0 : 30);

        $clockOut = $clockIn->copy()
            ->addHours(rand(8, 10));

        return [
            'user_id' => User::factory(),
            'work_date' => Carbon::instance($workDate)->format('Y-m-d'),
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'status' => 'approved',
        ];
    }
}
