<?php

namespace App\Services;

use App\Models\Flat;
use App\Models\MaintenanceRecord;
use Carbon\Carbon;
use App\Models\SocietySetting;
use App\Models\SocietyFlatTypeMaintenance;

class MaintenanceCalculatorService
{
    public function calculate(array $data, int $societyId): array
    {
        /* ---------------- FLAT ---------------- */
        $flat = Flat::with('flatType')->findOrFail($data['flat_type_id']);

        /* ---------------- BASE MAINTENANCE ---------------- */
        $flatMaintenance = SocietyFlatTypeMaintenance::where([
            'society_id' => $societyId,
            'society_flat_type_id' => $flat->flatType->id,
            'status' => 1
        ])->firstOrFail();

        $baseAmount = $flatMaintenance->maintenance_amount;

        /* ---------------- SOCIETY SETTINGS ---------------- */
        $settings = SocietySetting::where('society_id', $societyId)->first();

        $lateFee     = $settings->maintenance_late_fee ?? 0;
        $feeType     = $settings->late_fee_type ?? 'fixed';
        $graceDays   = $settings->grace_days ?? 0;

        /* ---------------- DATE RANGE ---------------- */
        $start = Carbon::create($data['from_year'], $data['from_month'], 1);

        $end = (!empty($data['to_year']) && !empty($data['to_month']))
            ? Carbon::create($data['to_year'], $data['to_month'], 1)
            : $start;

        if ($start->gt($end)) {
            throw new \Exception('Invalid date range');
        }

        /* ---------------- CALCULATION ---------------- */
        $rows = [];
        $totalAmount = 0;
        $lateFeesTotal = 0;
        $current = $start->copy();

        while ($current->lte($end)) {
            $record = MaintenanceRecord::where([
                'year'        => $current->year,
                'month'       => $current->month,
                'society_id' => $societyId,
                'flat_id' => $data['flat_type_id'],
                //'status' => 'pending'
            ])->value('status');
            $lateAmount = 0;
            if ($record !== 'paid') {

                $dueDate = Carbon::create(
                    $current->year,
                    $current->month,
                    1
                )->addDays($graceDays);

                if (now()->gt($dueDate)) {
                    $lateAmount = ($feeType === 'percentage')
                        ? ($baseAmount * $lateFee) / 100
                        : $lateFee;
                }

                $monthTotal = $baseAmount + $lateAmount;
                $totalAmount += $monthTotal;
                $lateFeesTotal += $lateAmount;
                $rows[] = [
                    'year'        => $current->year,
                    'month'       => $current->month,
                    'month_name'  => $current->format('F'),
                    'base_amount' => $baseAmount,
                    'late_fee'    => $lateAmount,
                    'total'       => $monthTotal,
                ];
            }

            $current->addMonth();
        }

        return [
            'base_amount'  => $baseAmount,
            'months'       => count($rows),
            'total_amount' => $totalAmount,
            'monthly_breakup'    => $rows,
            'late_fees_total' => $lateFeesTotal
        ];
    }
}
