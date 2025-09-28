<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_user_can_see_their_expenses()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->get('/expenses')
             ->assertStatus(200)
             ->assertSee($expense->amount);
    }

    public function test_user_cannot_see_others_expenses()
    {
        $otherUser = User::factory()->create();
        $expense = Expense::factory()->create(['user_id' => $otherUser->id]);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->get('/expenses')
             ->assertStatus(200)
             ->assertDontSee($expense->amount);
    }

    public function test_user_can_add_an_expense()
    {
        $expenseData = [
            'amount' => 100.00,
            'category' => 'Food',
            'date' => '2023-01-01',
            'notes' => 'Test expense',
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->post('/expenses', $expenseData)
             ->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', array_merge($expenseData, ['user_id' => $this->user->id]));
    }

    public function test_user_can_edit_their_expense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $updatedData = [
            'amount' => 200.00,
            'category' => 'Travel',
            'date' => '2023-01-02',
            'notes' => 'Updated expense',
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->put('/expenses/' . $expense->id, $updatedData)
             ->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', $updatedData);
    }

    public function test_user_can_delete_their_expense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])
             ->delete('/expenses/' . $expense->id)
             ->assertRedirect(route('expenses.index'));

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}