<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Support\Facades\URL;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verification_email_is_sent_after_registration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => '山田太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_verification_button_links_to_email_verification_site()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');
        $response->assertSee('http://localhost:8025');
    }

    public function test_verified_is_redirected_to_attendance_page()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

        $response = $this->get($verificationUrl);

        $response->assertRedirect(route('attendance.index'));


    }
}
