<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaintenanceRecordsExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $records;

    public function __construct(Collection $records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Year',
            'Month',
            'Building',
            'Flat No',
            'Flat Type',
            'Amount',
            'Payment Mode',
            'Status',
            'Paid On',
        ];
    }

    public function map($row): array
    {
        return [
            $row->year,
            $row->month_name,
            $row->building->name ?? '',
            $row->flat->flat_no ?? '',
            $row->flat->flatType->name ?? '',
            $row->amount,
            $row->payment_mode,
            ucfirst($row->status),
            optional($row->paid_on)->format('d-m-Y'),
        ];
    }
}
