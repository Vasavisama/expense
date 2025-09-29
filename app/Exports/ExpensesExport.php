<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->expenses;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'User',
            'Email',
            'Category',
            'Amount',
            'Date',
        ];
    }

    /**
     * @param Expense $expense
     * @return array
     */
    public function map($expense): array
    {
        return [
            $expense->user->name,
            $expense->user->email,
            $expense->category,
            $expense->amount,
            $expense->date,
        ];
    }
}