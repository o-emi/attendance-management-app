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

}
