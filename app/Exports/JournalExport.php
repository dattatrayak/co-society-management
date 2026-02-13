<?php

namespace App\Exports;

use App\Http\Repository\AccountRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JournalExport implements FromCollection, WithHeadings
{
    protected $request;
    protected $acountRepository;
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
        $this->acountRepository = app(AccountRepository::class);
    }

    public function collection()
    {
        $request = $this->request;

        $data =  $this->acountRepository->getForExport($request);

        return $data->map(function ($e) {

            // Accounting format rows (Double entry)

            if ($e->transaction_type == 'income') {
                return [
                    [
                        'Date' => $e->transaction_date,
                        'Voucher' => 'JV-' . $e->id,
                        'Particulars' => ucfirst($e->payment_mode) . ' A/c Dr',
                        'Debit' => $e->amount,
                        'Credit' => '',
                    ],
                    [
                        'Date' => '',
                        'Voucher' => '',
                        'Particulars' => 'To ' . $e->category->name,
                        'Debit' => '',
                        'Credit' => $e->amount,
                    ],
                ];
            } else {
                return [
                    [
                        'Date' => $e->transaction_date,
                        'Voucher' => 'JV-' . $e->id,
                        'Particulars' => $e->category->name . ' A/c Dr',
                        'Debit' => $e->amount,
                        'Credit' => '',
                    ],
                    [
                        'Date' => '',
                        'Voucher' => '',
                        'Particulars' => 'To ' . ucfirst($e->payment_mode) . ' A/c',
                        'Debit' => '',
                        'Credit' => $e->amount,
                    ],
                ];
            }
        })->flatten(1);
    }

    public function headings(): array
    {
        return ['Date', 'Voucher', 'Particulars', 'Debit', 'Credit'];
    }
}
