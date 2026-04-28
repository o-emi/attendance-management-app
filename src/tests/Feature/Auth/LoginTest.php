<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_is_required()
    {
        User::factory()->create([
            'email' =>'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => '', 'password' => 'password',
            ]);

        $response->assertSessionHasErrors([ 'email' => 'メールアドレスを入力してください',
        ]);
    }
}