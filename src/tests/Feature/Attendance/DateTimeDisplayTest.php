<?php

namespace Tests\Feature\Attendance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class DateTimeDisplayTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_current_datetime_is_displayed_in_correct_format()
    {
        Carbon::setTestNow(Carbon::create(2024, 4, 1, 9, 30));

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);

        $expected = Carbon::now()->locale('ja')->isoFormat('YYYY年M月D日(ddd)');

        $response->assertSee($expected);
    }
}
