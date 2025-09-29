<?php

namespace App\Imports;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ExpensesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Expense([
            'user_id'  => Auth::id(),
            'amount'   => $row['amount'],
            'category' => $row['category'],
            'date'     => Carbon::createFromFormat('d/m/Y', $row['date'])->format('Y-m-d'),
            'notes'    => $row['notes'],
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount'   => 'required|numeric',
            'category' => 'required|string|in:Food,Rent,Travel,Shopping',
            'date'     => 'required|date_format:d/m/Y',
            'notes'    => 'nullable|string',
        ];
    }
}