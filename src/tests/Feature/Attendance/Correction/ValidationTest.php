<?php

namespace Tests\Feature\Attendance\Correction;

use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
