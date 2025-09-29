<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\AnalyticsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    public function index()
    {
        $topSpenders = User::select('users.name', 'users.email', DB::raw('SUM(expenses.amount) as total_spent'))
            ->join('expenses', 'users.id', '=', 'expenses.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return view('dashboard.analytics.index', compact('topSpenders'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string|in:full_list,monthly_summary,yearly_summary',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'format' => 'required|string|in:csv,excel,pdf',
            'category' => 'nullable|string',
        ]);

        $reportType = $request->input('report_type');
        $format = $request->input('format');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $category = $request->input('category');

        $data = $this->getReportData($reportType, $fromDate, $toDate, $category);

        $filename = 'analytics_report_' . date('Y-m-d') . '.' . ($format == 'excel' ? 'xlsx' : $format);

        if ($format === 'csv' || $format === 'excel') {
            return Excel::download(new AnalyticsExport($data, $reportType), $filename);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('dashboard.analytics.pdf', ['data' => $data, 'reportType' => $reportType]);
            return $pdf->download($filename);
        }

        return redirect()->back()->with('error', 'Invalid export format.');
    }

    private function getReportData($reportType, $fromDate, $toDate, $category)
    {
        $query = User::query()
            ->join('expenses', 'users.id', '=', 'expenses.user_id');

        if ($reportType === 'full_list') {
            $query->select('users.name', 'users.email', 'expenses.description', 'expenses.amount', 'expenses.date', 'expenses.category')
                ->when($fromDate, fn($q) => $q->where('expenses.date', '>=', $fromDate))
                ->when($toDate, fn($q) => $q->where('expenses.date', '<=', $toDate))
                ->when($category, fn($q) => $q->where('expenses.category', $category))
                ->orderBy('expenses.date', 'desc');
        } elseif ($reportType === 'monthly_summary') {
            $query->select(DB::raw('YEAR(expenses.date) as year, MONTH(expenses.date) as month, SUM(expenses.amount) as total_amount'))
                ->when($fromDate, fn($q) => $q->where('expenses.date', '>=', $fromDate))
                ->when($toDate, fn($q) => $q->where('expenses.date', '<=', $toDate))
                ->when($category, fn($q) => $q->where('expenses.category', $category))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')->orderBy('month', 'desc');
        } elseif ($reportType === 'yearly_summary') {
            $query->select(DB::raw('YEAR(expenses.date) as year, SUM(expenses.amount) as total_amount'))
                ->when($fromDate, fn($q) => $q->where('expenses.date', '>=', $fromDate))
                ->when($toDate, fn($q) => $q->where('expenses.date', '<=', $toDate))
                ->when($category, fn($q) => $q->where('expenses.category', $category))
                ->groupBy('year')
                ->orderBy('year', 'desc');
        }

        return $query->get();
    }
}