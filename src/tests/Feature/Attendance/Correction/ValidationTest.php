<?php

namespace Tests\Feature\Attendance\Correction;

use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_error_message_is_displayed_when_clock_in_is_after_clock_out()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-15',
            'clock_in' => '2026-05-15 09:00:00',
            'clock_out' => '2026-05-15 18:00:00',
        ]);

        $response = $this->actingAs($user)
            ->post(route('attendance.request', $attendance->id),[
                'clock_in' => '19:00',
                'clock_out' => '18:00',
                'remark' => '修正申請',
            ]);

        $response->assertSessionHasErrors([
            'clock_in' => '出勤時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_error_message_is_displayed_when_break_start_is_after_clock_out()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-15',
            'clock_in' => '2026-05-15 09:00:00',
            'clock_out' => '2026-05-15 18:00:00',
        ]);

        $response = $this->actingAs($user)
            ->post(route('attendance.request', $attendance->id), [
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
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-15',
            'clock_in' => '2026-05-15 09:00:00',
            'clock_out' => '2026-05-15 18:00:00',
        ]);

        $response = $this->actingAs($user)
            ->post(route('attendance.request', $attendance->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'break_start' => ['12:00'],
                'break_end' => ['18:30'],
                'remark' => '修正申請',
            ]);

        $response->assertSessionHasErrors([
            'break_end.0' => '休憩時間もしくは退勤時間が不適切な値です',
        ]);
    }

    public function test_error_message_is_displayed_when_remark_is_empty()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-15',
            'clock_in' => '2026-05-15 09:00:00',
            'clock_out' => '2026-05-15 18:00:00',
        ]);

        $response = $this->actingAs($user)
            ->post(route('attendance.request', $attendance->id), [
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'break_start' => ['12:00'],
                'break_end' => ['13:00'],
                'remark' => '',
            ]);

        $response->assertSessionHasErrors([
            'remark'=> '備考を記入してください',
        ]);
    }
}
