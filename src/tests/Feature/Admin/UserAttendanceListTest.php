<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAttendanceListTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_attendance_is_displayed_correctly()
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create([
            'role' => 'user'
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-06-01',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => '12:00',
            'break_end' => '13:00',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.staff.attendance', [
                'id' => $user->id,
                'month' => '2026-06',
        ]));
        
        $response->assertStatus(200);
        $response->assertSee(Carbon::parse('2026-06-01')->locale('ja')->isoFormat('MM/DD(ddd)'));
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('01:00');
        $response->assertSee('08:00');
    }

    public function test_previous_month_attendance_is_displayed()
    {
        Carbon::setTestNow('2026-06-01 09:00:00');

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => '2026-05-31',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);;

        $response = $this->actingAs($admin)
            ->get(route('admin.staff.attendance', [
                'id' => $user->id,
                'month' => '2026-05',
        ]));

        $response->assertStatus(200);
        $response->assertSee('2026/05');

        Carbon::setTestNow();
    }

    public function test_next_month_attendance_is_displayed()
    {
        Carbon::setTestNow('2026-06-01 09:00:00');

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => '2026-07-01',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.staff.attendance', [
                'id' => $user->id,
                'month' => '2026-07',
        ]));

        $response->assertStatus(200);
        $response->assertSee('2026/07');

        Carbon::setTestNow();
    }


}
