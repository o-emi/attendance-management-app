<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserListTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_users_name_and_email_on_staff_list()
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user1 = User::factory()->create([
            'name' => '山田太郎',
            'email' =>'yamada@example.com',
            'role' => 'user',
        ]);

        $user2 = User::factory()->create([
            'name' => '佐藤花子',
            'email' =>'satou@example.com',
            'role' => 'user',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.staff.list'));

        $response->assertStatus(200);
        $response->assertSee($user1->name);
        $response->assertSee($user1->email);
        $response->assertSee($user2->name);
        $response->assertSee($user2->email);
    }
}
