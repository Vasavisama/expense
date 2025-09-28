<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpensesExport implements FromArray, WithHeadings
{
    protected $data;
    protected $reportType;

    public function __construct($data, $reportType)
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    /**
    * @return array
    */
    public function array(): array
    {
        return $this->data->map(function ($row) {
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
        })->toArray();
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