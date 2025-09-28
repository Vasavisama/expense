<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $reportType;

    public function __construct($data, $reportType)
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    /**
    * @param mixed $row
    * @return array
    */
    public function map($row): array
    {
        switch ($this->reportType) {
            case 'monthly_summary':
                return [
                    $row->year,
                    $row->month,
                    $row->total_amount,
                ];
            case 'yearly_summary':
                return [
                    $row->year,
                    $row->total_amount,
                ];
            case 'full_list':
            default:
                return [
                    $row->amount,
                    $row->category,
                    $row->date,
                    $row->notes,
                ];
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        switch ($this->reportType) {
            case 'monthly_summary':
                return ['Year', 'Month', 'Total Amount'];
            case 'yearly_summary':
                return ['Year', 'Total Amount'];
            case 'full_list':
            default:
                return ['Amount', 'Category', 'Date', 'Notes'];
        }
    }
}