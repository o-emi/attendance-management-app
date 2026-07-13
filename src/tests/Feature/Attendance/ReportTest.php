<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;

class ReportTest extends TestCase
{
    use RefreshDatabase;
    public function test_report_page_is_displayed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('attendance.report'));

        $response->assertStatus(200);
    }

    public function test_summary_information_is_displayed()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-04-16',
            'clock_in' => '2026-04-16 10:00:00',
            'clock_out' => '2026-04-16 19:00:00',
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => '2026-04-16 12:00:00',
            'break_end' => '2026-04-16 13:00:00',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('attendance.report'));

        $response->assertStatus(200);

        $response->assertSee('8h 0m');

    }
}
