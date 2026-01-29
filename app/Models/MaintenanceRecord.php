<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRecord extends Model
{
    use SoftDeletes;

    protected $table = 'maintenance_records';

    protected $fillable = [
        'society_id',
        'building_id',
        'flat_id',
        'cash_transactions_id',
        'year',
        'month',
        'amount',
        'payment_mode',
        'check_no',
        'attachment',
        'status',
        'paid_on',
        'note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'year'     => 'integer',
        'month'    => 'integer',
        'amount'   => 'decimal:2',
        'paid_on'  => 'date',
        'deleted_at' => 'datetime',
    ];
    public function society()
    {
        return $this->belongsTo(SocietyUser::class, 'society_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function scopeForMonth($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }
    public function cashTransaction()
    {
        return $this->belongsTo(CashTransaction::class, 'cash_transactions_id');
    }
}
