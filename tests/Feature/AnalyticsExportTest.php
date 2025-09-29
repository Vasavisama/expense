<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnalyticsExport;
use Tests\TestCase;

class AnalyticsExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_full_list_report_with_category_filter()
    {
        Excel::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        Expense::factory()->create([
            'user_id' => $user->id,
            'category' => 'Food',
            'amount' => 100,
            'date' => '2023-01-15'
        ]);

        Expense::factory()->create([
            'user_id' => $user->id,
            'category' => 'Travel',
            'amount' => 200,
            'date' => '2023-01-20'
        ]);

        $response = $this->actingAs($admin)->post(route('analytics.export'), [
            'report_type' => 'full_list',
            'from_date' => '2023-01-01',
            'to_date' => '2023-01-31',
            'category' => 'Food',
            'format' => 'excel',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename=analytics_report_' . date('Y-m-d') . '.xlsx');

        Excel::assertDownloaded('analytics_report_' . date('Y-m-d') . '.xlsx', function (AnalyticsExport $export) {
            // Assert that the export contains 1 row
            $this->assertCount(1, $export->collection());
            // Assert that the category of the exported data is 'Food'
            $this->assertEquals('Food', $export->collection()->first()->category);
            return true;
        });
    }

    public function test_admin_can_export_full_list_report_without_category_filter()
    {
        Excel::fake();

        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create();

        Expense::factory()->create([
            'user_id' => $user->id,
            'category' => 'Food',
            'amount' => 100,
            'date' => '2023-01-15'
        ]);

        Expense::factory()->create([
            'user_id' => $user->id,
            'category' => 'Travel',
            'amount' => 200,
            'date' => '2023-01-20'
        ]);

        $response = $this->actingAs($admin)->post(route('analytics.export'), [
            'report_type' => 'full_list',
            'from_date' => '2023-01-01',
            'to_date' => '2023-01-31',
            'category' => '',
            'format' => 'excel',
        ]);

        $response->assertStatus(200);

        Excel::assertDownloaded('analytics_report_' . date('Y-m-d') . '.xlsx', function (AnalyticsExport $export) {
            // Assert that the export contains 2 rows
            $this->assertCount(2, $export->collection());
            return true;
        });
    }
}