<?php

namespace Tests\Feature\Attendance\Correction;

use App\Models\User;
use App\Models\Attendance;
use App\Models\CorrectionRequest;
use App\Models\CorrectionRequestBreakTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestListTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_is_displayed_on_request_list()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => '2026-05-15',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        $correctionRequest = CorrectionRequest::create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'start_time' => '10:00',
            'end_time' => '19:00',
            'note' => '電車遅延のため',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->get(route('stamp_correction_request.list'));

        $response->assertStatus(200);
        $response->assertSee($correctionRequest->user->name);
        $response->assertSee($attendance->work_date->format('Y/m/d'));
        $response->assertSee($correctionRequest->note);
    }
}
