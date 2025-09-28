<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpensesExport implements FromArray, WithHeadings
{
    protected $data;
    protected $reportType;
    protected $isAdminExport;

    public function __construct($data, $reportType, $isAdminExport = false)
    {
        $this->data = $data;
        $this->reportType = $reportType;
        $this->isAdminExport = $isAdminExport;
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
                    $rowData = [
                        $row->amount,
                        $row->category,
                        $row->date,
                        $row->notes,
                    ];

                    if ($this->isAdminExport && isset($row->user)) {
                        array_unshift($rowData, $row->user->name);
                    }

                    return $rowData;
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
                $headings = ['Amount', 'Category', 'Date', 'Notes'];

                if ($this->isAdminExport) {
                    array_unshift($headings, 'User');
                }

                return $headings;
        }
    }
}