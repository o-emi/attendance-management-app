<?php

namespace Tests\Feature\Attendance;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceListTest extends TestCase
{
    public function test_all_own_attendance_records_are_displayed()
    {
        $user = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-13',
            'clock_in' => '2026-05-13 09:00:00',
            'clock_out' => '2026-05-13 18:00:00',
        ]);

        $otherUser = User::factory()->create();

        Attendance::factory()->create([
            'user_id' => $otherUser->id,
            'work_date' => '2026-05-13',
            'clock_in' => '2026-05-13 18:30:00',
            'clock_out' => '2026-05-13 19:30:00',
        ]);

        $response = $this->actingAs($user)
            ->get('/attendance/list');

        $response->assertSee('09:00');
        $response->assertDontSee('18:30');
    }

    public function test_current_month_is_displayed_on_attendance_list()
    {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create('2026-05-15'));

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertSeeText('2026/05');
    }

    public function test_previous_month_attendance_records_are_displayed()
    {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create('2026-05-15'));

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-04-16',
            'clock_in' => '2026-04-16 10:00:00',
            'clock_out' => '2026-04-16 19:00:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2026-04');

        $response->assertSeeText('2026/04');
        $response->assertSeeText('10:00');
    }

     // 翌月ボタンで翌月の情報が表示される
    public function test_next_month_attendance_records_are_displayed()
    {
        $user = User::factory()->create();

        Carbon::setTestNow(Carbon::create('2026-05-15'));

        Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-06-16',
            'clock_in' => '2026-06-16 10:00:00',
            'clock_out' => '2026-06-16 19:00:00',
        ]);

        $response = $this->actingAs($user)->get('/attendance/list?month=2026-06');

        $response->assertSeeText('2026/06');
        $response->assertSeeText('10:00');
    }


        // 詳細ボタンで勤怠詳細画面に遷移する
    public function test_clicking_detail_link_redirects_to_attendance_detail_page()
    {
        $user = User::factory()->create();

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-15',
            'clock_in' => '2026-05-15 10:00:00',
            'clock_out' => '2026-05-15 19:00:00',
        ]);

        $response = $this->actingAs($user)
            ->get('/attendance/detail/' . $attendance->id);
        $response->assertStatus(200);
        $response->assertSeeText('勤怠詳細');
    }
}
