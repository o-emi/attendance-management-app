<?php

namespace Tests\Feature\Attendance;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreakTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_changes_to_on_break_when_break_started()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
            'clock_in' => now(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)
            ->get('/attendance')
            ->assertSeeText('休 憩 入');

        $this->actingAs($user)
            ->post('/attendance/break/start'); // ここはルートに合わせて

        $this->get('/attendance')
            ->assertSee('休憩中');
    }

    public function test_status_returns_to_working_when_break_ended()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
            'clock_in' => now(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)
            ->post('/attendance/break/start');

        $this->get('/attendance')
            ->assertSee('休 憩 戻');

        $this->post('/attendance/break/end');

        $this->get('/attendance')
            ->assertSee('出勤中');
    }

    public function test_break_start_button_is_displayed_after_break_end()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->toDateString(),
            'clock_in' => now(),
            'status' => '出勤中',
        ]);

        $this->actingAs($user)
            ->post('/attendance/break/start');

        $this->post('/attendance/break/end');

        $this->get('/attendance')
            ->assertSeeText('休 憩 入');
    }

}
