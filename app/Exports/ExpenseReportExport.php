<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExpenseReportExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $sr = 1;

        return Expense::with(['cashCategory', 'member', 'cashTransaction'])

            ->get()
            ->map(function ($expense) use (&$sr) {
                return [
                    'Sr No'       => $sr++,
                    'Date'        => $expense->expense_date,
                    'Category'    => $expense->cashCategory->name ?? '',
                    'Paid To'     => $expense->paid_to == 'member'
                        ? ($expense->member->name ?? '')
                        : $expense->paid_to_name,
                    'Amount'      => $expense->amount,
                    'Payment Mode' => $expense->payment_mode,
                    'Reference No' => $expense->reference_no,
                    'Status'      => $expense->status,
                    'Note'        => $expense->note,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Category',
            'Paid To',
            'Amount',
            'Payment Mode',
            'Reference No',
            'Status',
            'Note',
        ];
    }
}
