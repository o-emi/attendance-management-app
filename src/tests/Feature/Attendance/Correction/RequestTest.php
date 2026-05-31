<?php

namespace Tests\Feature\Attendance\Correction;

use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\CorrectionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_correction_request_is_created()
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
                'clock_in' => '09:00',
                'clock_out' => '18:00',
                'break_start' => ['13:00'],
                'break_end' => ['14:00'],
                'remark' => '修正申請テスト',
            ]);

        $this->assertDatabaseHas('correction_requests', [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'note' => '修正申請テスト',
        ]);
    }
}