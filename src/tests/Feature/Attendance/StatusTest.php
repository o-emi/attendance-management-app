<?php

namespace Tests\Feature\Attendance;

use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_is_off_duty_when_not_clocked_in()
    {
        Carbon::setTestNow('2026-05-02 09:00:00');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::today()->toDateString(),
            'clock_in' => null,
            'clock_out' => null,
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('勤務外');
    }

    public function test_status_is_working_when_clocked_in()
    {
        Carbon::setTestNow('2026-05-02 09:00:00');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::today()->toDateString(),
            'clock_in' => Carbon::today()->setTime(9, 0),
            'clock_out' => null,
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    public function test_status_is_on_break_when_break_started()
    {
        Carbon::setTestNow('2026-05-02 12:00:00');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::today()->toDateString(),
            'clock_in' => Carbon::today()->setTime(9, 0),
            'clock_out' => null,
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => Carbon::today()->setTime(12, 0),
            'break_end' => null,
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('休憩中');
    }

    public function test_status_is_finished_when_clocked_out()
    {
        Carbon::setTestNow('2026-05-02 18:00:00');

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::today()->toDateString(),
            'clock_in' => Carbon::today()->setTime(9, 0),
            'clock_out' => Carbon::today()->setTime(18, 0),
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('退勤済');
    }
}
