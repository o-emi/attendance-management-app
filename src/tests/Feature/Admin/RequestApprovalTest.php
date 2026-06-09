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
            'start_time' => '09:00',
            'end_time' => '18:00',
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
        $response->assertSee($correctionRequest->start_time);
        $response->assertSee($correctionRequest->end_time);
        $response->assertSee($correctionRequest->note);
    }

    public function test_admin_can_approve_correction_request()
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
            'remark' => '元の備考',
        ]);

        $correctionRequest = CorrectionRequest::create([
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'start_time' => '10:00',
            'end_time' => '19:00',
            'note' => '電車遅延のため',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.request.approve', $correctionRequest->id));

        $response->assertRedirect(route('admin.request.list', [
            'status' => 'approved',
        ]));

        $this->assertDatabaseHas('correction_requests', [
            'id' => $correctionRequest->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'clock_in' => now()->format('Y-m-d') . ' 10:00:00',
            'clock_out' => now()->format('Y-m-d') . ' 19:00:00',
            'remark' => '電車遅延のため',
        ]);
    }
}
