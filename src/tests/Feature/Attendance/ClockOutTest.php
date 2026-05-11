<?php

namespace Tests\Feature\Attendance;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClockOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_out_changes_status_to_finished()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
            'clock_in' => now(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)
            ->get('/attendance')
            ->assertSeeText('退 勤');

        $this->actingAs($user)
            ->post('/attendance');

        $this->actingAs($user)
            ->get('/attendance')
            ->assertSeeText('退勤済');
    }

    public function test_clock_out_time_is_displayed_in_attendance_list()
    {

        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
            'clock_in' => now(),
            'status' => '出勤中',
        ]);

        Carbon::setTestNow('2026-05-11 18:00:00');

        $this->actingAs($user)
            ->post('/attendance');

        $this->actingAs($user)
            ->get('/attendance/list')
            ->assertSee('18:00');
    }
}
