<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function createAndLoginUser($role = 'user')
    {
        $user = User::factory()->create(['role' => $role]);
        $token = JWTAuth::fromUser($user);
        $this->withCookie('jwt_token', $token);
        return $user;
    }

    public function test_non_admin_cannot_access_admin_panel()
    {
        $user = $this->createAndLoginUser('user');

        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = $this->createAndLoginUser('admin');

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_admin_can_view_all_users()
    {
        $admin = $this->createAndLoginUser('admin');
        User::factory()->count(5)->create();

        $response = $this->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('User Management');
        $this->assertCount(6, User::all()); // Including the admin user
    }

    public function test_admin_can_search_for_users()
    {
        $admin = $this->createAndLoginUser('admin');
        $userToFind = User::factory()->create(['name' => 'John Doe', 'email' => 'john.doe@example.com']);
        $userToIgnore = User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane.smith@example.com']);

        $response = $this->get('/admin/users?search=John');

        $response->assertStatus(200);
        $response->assertSee($userToFind->name);
        $response->assertDontSee($userToIgnore->name);
    }

    public function test_admin_can_edit_a_user()
    {
        $admin = $this->createAndLoginUser('admin');
        $user = User::factory()->create();

        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated.email@example.com',
            'role' => 'admin',
        ];

        $response = $this->put('/admin/users/' . $user->id, $updatedData);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_toggle_user_status()
    {
        $admin = $this->createAndLoginUser('admin');
        $user = User::factory()->create(['is_active' => true]);

        $response = $this->patch('/admin/users/' . $user->id . '/toggle-status');

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_view_top_spenders()
    {
        $admin = $this->createAndLoginUser('admin');
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Expense::factory()->create(['user_id' => $user1->id, 'amount' => 1000]);
        Expense::factory()->create(['user_id' => $user2->id, 'amount' => 500]);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee($user1->name);
        $response->assertSee('1,000.00');
    }

    public function test_admin_can_export_all_expenses_as_csv()
    {
        $admin = $this->createAndLoginUser('admin');
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Expense::factory()->create(['user_id' => $user1->id, 'amount' => 100]);
        Expense::factory()->create(['user_id' => $user2->id, 'amount' => 200]);

        $response = $this->post('/admin/expenses/export', [
            'report_type' => 'full_list',
            'format' => 'csv',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=expenses-full_list-'.now()->format('Y-m-d').'.csv');
        $content = $response->getContent();
        $this->assertStringContainsString('100', $content);
        $this->assertStringContainsString('200', $content);
    }
}