<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

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

    public function test_past_6_monthly_reports_are_displayed()
    {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create(2026, 4, 16));

        for ($i = 5; $i >= 0; $i--) {

            Attendance::factory()->create([
                'user_id' => $user->id,
                'work_date' => Carbon::now()->subMonths($i),
                'clock_in' => Carbon::now()->subMonths($i)->setTime(10, 0, 0),
                'clock_out' => Carbon::now()->subMonths($i)->setTime(19, 0, 0),
            ]);
        }

        $this->actingAs($user);

        $response = $this->get(route('attendance.report'));

        $response->assertStatus(200);

        $response->assertSee('2026/04');
        $response->assertSee('2026/03');
        $response->assertSee('2026/02');
        $response->assertSee('2026/01');
        $response->assertSee('2025/12');
        $response->assertSee('2025/11');

        Carbon::setTestNow();
    }

    public function test_this_month_anomaly_list_is_displayed()
    {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create(2026, 4, 16));

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-04-15',
            'clock_in' => '2026-04-16 10:00:00',
            'clock_out' => '2026-04-16 17:00:00',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-04-16',
            'clock_in' => '2026-04-16 8:00:00',
            'clock_out' => '2026-04-16 20:00:00',
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => '2026-04-16 12:00:00',
            'break_end' => '2026-04-16 13:00:00',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('attendance.report'));

        $response->assertStatus(200);

        $response->assertSee('遅刻回数');
        $response->assertSee('早退回数');
        $response->assertSee('長時間労働日数');

        $response->assertSee('1回');
        $response->assertSee('1日');

    }
}