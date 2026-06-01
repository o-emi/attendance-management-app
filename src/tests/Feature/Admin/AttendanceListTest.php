<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_users_attendance_are_displayed()
    {
        Carbon::setTestNow('2026-06-01 09:00:00');

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user1 = User::factory()->create([
            'name' => '山田太郎',
            'role' => 'user',
        ]);

        $user2 = User::factory()->create([
            'name' => '佐藤花子',
            'role' => 'user',
        ]);

        Attendance::create([
            'user_id' => $user1->id,
            'work_date' => '2026-06-01',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        Attendance::create([
            'user_id' => $user2->id,
            'work_date' => '2026-06-01',
            'clock_in' => '10:00',
            'clock_out' => '19:00',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.attendance.list'));

        $response->assertStatus(200);
        $response->assertSee('山田太郎');
        $response->assertSee('佐藤花子');
        $response->assertSee('09:00');
        $response->assertSee('18:00');
        $response->assertSee('10:00');
        $response->assertSee('19:00');

        Carbon::setTestNow();
    }

    public function test_current_date_is_displayed()
    {
        Carbon::setTestNow('2026-06-01 09:00:00');

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.attendance.list'));

        $response->assertStatus(200);
        $response->assertSee('2026年6月1日の勤怠');

        Carbon::setTestNow();
    }

    public function test_previous_day_attendance_is_displayed()
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
            ->get(route('admin.attendance.list', [
                'date' => '2026-05-31'
        ]));

        $response->assertStatus(200);
        $response->assertSee('2026年5月31日');

        Carbon::setTestNow();
    }

    public function test_next_day_attendance_is_displayed()
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
            'work_date' => '2026-06-02',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);;

        $response = $this->actingAs($admin)
            ->get(route('admin.attendance.list', [
                'date' => '2026-06-02'
        ]));

        $response->assertStatus(200);
        $response->assertSee('2026年6月2日');

        Carbon::setTestNow();
    }

}
