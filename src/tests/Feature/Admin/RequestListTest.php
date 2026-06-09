<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Attendance;
use App\Models\CorrectionRequest;
use App\Models\CorrectionRequestBreakTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestListTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_is_displayed_on_admin_request_list()
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
            'start_time' => '10:00',
            'end_time' => '19:00',
            'note' => '電車遅延のため',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.request.list'));

        $response->assertStatus(200);
        $response->assertSee($correctionRequest->user->name);
        $response->assertSee($attendance->work_date->format('Y/m/d'));
        $response->assertSee($correctionRequest->note);
    }

    public function test_all_request_is_displayed_on_admin_request_list()
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
            'start_time' => '10:00',
            'end_time' => '19:00',
            'note' => '電車遅延のため',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.request.list'));

        $response->assertStatus(200);
        $response->assertSee($correctionRequest->user->name);
        $response->assertSee($attendance->work_date->format('Y/m/d'));
        $response->assertSee($correctionRequest->note);
    }

    public function test_approved_request_is_displayed_on_admin_request_list()
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
            'start_time' => '10:00',
            'end_time' => '19:00',
            'note' => '電車遅延のため',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.request.list', ['status' => 'approved']));

        $response->assertStatus(200);
        $response->assertSee($correctionRequest->user->name);
        $response->assertSee($attendance->work_date->format('Y/m/d'));
        $response->assertSee($correctionRequest->note);
    }

}


