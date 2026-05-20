<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Attendance;
use App\Models\CorrectionRequest;
use App\Models\CorrectionRequestBreakTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_is_displayed_on_admin_page()
    {
        $user = User::factory()->create();

        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => '2026-05-15',
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        $correctionRequest = CorrectionRequest::create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'clock_in' => '10:00',
            'clock_out' => '19:00',
            'note' => '電車遅延のため',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.request.show', $correctionRequest->id));

        $response->assertStatus(200);
        $response->assertSee($correctionRequest->clock_in);
        $response->assertSee($correctionRequest->clock_out);
        $response->assertSee($correctionRequest->note);
    }
}
