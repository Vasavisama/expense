<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function createAndLoginUser($role = 'user')
    {
        $user = User::factory()->create(['role' => $role]);
        $token = JWTAuth::fromUser($user);
        $this->withCookie('jwt_token', $token);
        return $user;
    }

    public function test_guest_cannot_access_expenses()
    {
        $this->get('/expenses')->assertRedirect('/login');
        $this->get('/expenses/create')->assertRedirect('/login');
        $this->post('/expenses')->assertRedirect('/login');
    }

    public function test_user_can_view_their_own_expenses()
    {
        $user = $this->createAndLoginUser();
        $expense1 = Expense::factory()->create(['user_id' => $user->id, 'amount' => 100]);
        $expense2 = Expense::factory()->create(['user_id' => $user->id, 'amount' => 200]);

        // Create an expense for another user that should not be visible
        $otherUser = User::factory()->create();
        Expense::factory()->create(['user_id' => $otherUser->id, 'amount' => 300]);

        $response = $this->get('/expenses');

        $response->assertStatus(200);
        $response->assertSee('100');
        $response->assertSee('200');
        $response->assertDontSee('300');
    }

    public function test_user_sees_message_when_no_expenses_added()
    {
        $this->createAndLoginUser();

        $response = $this->get('/expenses');

        $response->assertStatus(200);
        $response->assertSee('No expenses added.');
    }

    public function test_user_can_create_an_expense()
    {
        $user = $this->createAndLoginUser();

        $expenseData = [
            'amount' => 150.75,
            'category' => 'Food',
            'date' => '2025-01-15',
            'notes' => 'Lunch meeting',
        ];

        $response = $this->post('/expenses', $expenseData);

        $response->assertRedirect('/expenses');
        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'amount' => 150.75,
            'category' => 'Food',
        ]);
    }

    public function test_user_can_edit_their_own_expense()
    {
        $user = $this->createAndLoginUser();
        $expense = Expense::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'amount' => 99.99,
            'category' => 'Shopping',
            'date' => '2025-02-20',
            'notes' => 'New shoes',
        ];

        $response = $this->put('/expenses/' . $expense->id, $updatedData);

        $response->assertRedirect('/expenses');
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'amount' => 99.99,
            'category' => 'Shopping',
        ]);
    }

    public function test_user_cannot_edit_others_expense()
    {
        $user = $this->createAndLoginUser();
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->get('/expenses/' . $expense->id . '/edit');
        $response->assertStatus(403);

        $response = $this->put('/expenses/' . $expense->id, ['amount' => 50]);
        $response->assertStatus(403);
    }

    public function test_user_can_delete_their_own_expense()
    {
        $user = $this->createAndLoginUser();
        $expense = Expense::factory()->create(['user_id' => $user->id]);

        $response = $this->delete('/expenses/' . $expense->id);

        $response->assertRedirect('/expenses');
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_user_cannot_delete_others_expense()
    {
        $this->createAndLoginUser();
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->delete('/expenses/' . $expense->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('expenses', ['id' => $expense->id]);
    }

    public function test_create_expense_validation()
    {
        $this->createAndLoginUser();

        $response = $this->post('/expenses', []);
        $response->assertSessionHasErrors(['amount', 'category', 'date']);

        $response = $this->post('/expenses', ['amount' => 'not-a-number']);
        $response->assertSessionHasErrors('amount');

        $response = $this->post('/expenses', ['category' => 'Invalid Category']);
        $response->assertSessionHasErrors('category');
    }

    public function test_user_can_export_expenses_as_csv()
    {
        $user = $this->createAndLoginUser();
        Expense::factory()->create(['user_id' => $user->id, 'amount' => 100, 'category' => 'Food', 'date' => '2025-01-01', 'notes' => 'Lunch']);
        Expense::factory()->create(['user_id' => $user->id, 'amount' => 200, 'category' => 'Travel', 'date' => '2025-01-02', 'notes' => 'Train ticket']);

        $response = $this->post('/expenses/export', [
            'report_type' => 'full_list',
            'format' => 'csv',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename=expenses-full_list-'.now()->format('Y-m-d').'.csv');

        $content = $response->getContent();
        $this->assertStringContainsString('Amount,Category,Date,Notes', $content);
        $this->assertStringContainsString('100,Food,2025-01-01,Lunch', $content);
        $this->assertStringContainsString('200,Travel,2025-01-02,Train ticket', $content);
    }

    public function test_user_can_export_expenses_as_pdf()
    {
        $user = $this->createAndLoginUser();
        Expense::factory()->create(['user_id' => $user->id]);

        $response = $this->post('/expenses/export', [
            'report_type' => 'full_list',
            'format' => 'pdf',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename=expenses-full_list-'.now()->format('Y-m-d').'.pdf');
    }

    public function test_user_can_export_expenses_as_xlsx()
    {
        $user = $this->createAndLoginUser();
        Expense::factory()->create(['user_id' => $user->id]);

        $response = $this->post('/expenses/export', [
            'report_type' => 'full_list',
            'format' => 'xlsx',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('Content-Disposition', 'attachment; filename=expenses-full_list-'.now()->format('Y-m-d').'.xlsx');
    }

    public function test_user_can_export_filtered_expenses()
    {
        $user = $this->createAndLoginUser();
        $expenseToExclude1 = Expense::factory()->create(['user_id' => $user->id, 'amount' => 100, 'date' => '2025-01-01']);
        $expenseToInclude = Expense::factory()->create(['user_id' => $user->id, 'amount' => 200, 'date' => '2025-02-15']);
        $expenseToExclude2 = Expense::factory()->create(['user_id' => $user->id, 'amount' => 300, 'date' => '2025-03-01']);

        $response = $this->post('/expenses/export', [
            'report_type' => 'full_list',
            'format' => 'csv',
            'from_date' => '2025-02-01',
            'to_date' => '2025-02-28',
        ]);

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString((string) $expenseToInclude->amount, $content);
        $this->assertStringNotContainsString((string) $expenseToExclude1->amount, $content);
        $this->assertStringNotContainsString((string) $expenseToExclude2->amount, $content);
    }

    public function test_export_validation()
    {
        $this->createAndLoginUser();

        $response = $this->post('/expenses/export', []);
        $response->assertSessionHasErrors(['report_type', 'format']);

        $response = $this->post('/expenses/export', ['report_type' => 'invalid', 'format' => 'invalid']);
        $response->assertSessionHasErrors(['report_type', 'format']);

        $response = $this->post('/expenses/export', ['from_date' => 'not-a-date']);
        $response->assertSessionHasErrors('from_date');
    }
}