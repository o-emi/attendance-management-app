<?php

namespace Tests\Feature\Attendance;

use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_page_shows_logged_in_user_name()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-13',
            'clock_in' => '2026-05-13 09:00:00',
            'clock_out' => '2026-05-13 18:00:00',
        ]);

        $response = $this->actingAs($user)
            ->get(route('attendance.show', $attendance->id));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_detail_page_shows_selected_date()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-13',
            'clock_in' => '2026-05-13 09:00:00',
            'clock_out' => '2026-05-13 18:00:00',
        ]);

        $response = $this->actingAs($user)
            ->get(route('attendance.show', $attendance->id));

        $response->assertStatus(200);
        $response->assertSee('2026年');
        $response->assertSee('5月13日');
    }
// 出勤・退勤にて記されている時間がログインユーザーの打刻と一致しているか
    public function test_detail_page_shows_correct_clock_in_and_clock_out_times()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-13',
            'clock_in' => '2026-05-13 09:00:00',
            'clock_out' => '2026-05-13 18:00:00',
        ]);

        $response = $this->actingAs($user)
            ->get(route('attendance.show', $attendance->id));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '出勤・退勤',
            '09:00',
            '18:00',
        ]);
    }
// 休憩にて記されている時間がログインユーザーの打刻と一致しているか
    public function test_detail_page_shows_correct_break_times()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-13',
            'clock_in' => '2026-05-13 09:00:00',
            'clock_out' => '2026-05-13 18:00:00',
        ]);

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_start' => '2026-05-13 12:00:00',
            'break_end' => '2026-05-13 13:00:00',
        ]);

        $response = $this->actingAs($user)
            ->get(route('attendance.show', $attendance->id));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            '休憩',
            '12:00',
            '13:00',
        ]);
    }
}
