<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_selected_attendance_is_displayed_on_detail_page()
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create([
            'name' => '山田太郎',
        ]);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::create(2026, 6, 1),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.attendance.show', $attendance->id));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($attendance->work_date->format('Y年n月j日'));
        $response->assertSee($attendance->clock_in->format('H:i'));
        $response->assertSee($attendance->clock_out->format('H:i'));
    }

    public function test_error_message_is_displayed_when_clock_in_is_after_clock_out()
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::create(2026, 6, 1),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.show', $attendance->id))
            ->put(route('admin.attendance.update', $attendance->id), [
                'clock_in' => '19:00',
                'clock_out' => '18:00',
                'break_start' => [],
                'break_end' => [],
                'remark' => '修正申請',
            ]);

        $response->assertRedirect(route('admin.attendance.show', $attendance->id));
        $response->assertSessionHasErrors([
            'clock_in' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_error_message_is_displayed_when_break_start_is_after_clock_out()
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::create(2026, 6, 1),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.show', $attendance->id))
            ->put(route('admin.attendance.update', $attendance->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'break_start' => ['19:00'],
                'break_end' => ['17:30'],
                'remark' => '修正申請',
            ]);

        $response->assertSessionHasErrors([
            'break_start.0' => '休憩時間が不適切な値です',
        ]);
    }

    public function test_error_message_is_displayed_when_break_end_is_after_clock_out()
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => Carbon::create(2026, 6, 1),
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.show', $attendance->id))
            ->put(route('admin.attendance.update', $attendance->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'break_start' => ['19:00'],
                'break_end' => ['19:00'],
                'remark' => '修正申請',
            ]);

        $response->assertSessionHasErrors([
            'break_end.0' => '休憩時間が不適切な値です',
        ]);
    }
}
