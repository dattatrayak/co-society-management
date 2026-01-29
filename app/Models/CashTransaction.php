<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $fillable = [
        'society_id',
        'cash_category_id',
        'transaction_date',
        'transaction_type',
        'amount',
        'payment_mode',
        'reference_no',
        'description',
        'created_by',
        'updated_BY',
    ];

    public function category()
    {
        return $this->belongsTo(CashCategory::class, 'cash_category_id');
    }
    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class, 'cash_transactions_id');
    }
}
