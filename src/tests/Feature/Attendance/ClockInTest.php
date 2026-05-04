<?php

namespace Tests\Feature\Attendance;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClockInTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_in_changes_status_to_working()
    {

    // ユーザー作成
        $user = User::factory()->create();

    // ログイン
        $this->actingAs($user);

    // 出勤処理
        $this->post('/attendance');

    // 画面確認（UI）
        $this->get('/attendance')
        ->assertSee('出勤中');
    }

    public function test_clock_in_button_not_visible_after_clock_out()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post('/attendance'); // 出勤
        $this->post('/attendance'); // 退勤

        $this->get('/attendance')
        ->assertDontSee('出 勤');
    }

    public function test_clock_in_time_is_displayed_in_attendance_list()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post('/attendance');

        $this->get('/attendance/list')
        ->assertSee(now()->format('H:i'));
    }
}
