<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnalyticsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $reportType;

    public function __construct($data, $reportType)
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        if ($this->reportType === 'full_list') {
            return [
                'Name',
                'Email',
                'Description',
                'Amount',
                'Date',
                'Category',
            ];
        }

        if ($this->data->isEmpty()) {
            return [];
        }

        return array_keys((array) $this->data->first());
    }

    public function map($row): array
    {
        if ($this->reportType === 'full_list') {
            return [
                $row->name,
                $row->email,
                $row->description,
                $row->amount,
                $row->date,
                $row->category,
            ];
        }

        return (array) $row;
    }
}