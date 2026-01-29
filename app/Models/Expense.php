<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'society_id',
        'cash_category_id',
        'cash_transactions_id',
        'society_members_id',
        'frequency',
        'expense_date',
        'amount',
        'payment_mode',
        'check_no',
        'attachment',
        'reference_no',
        'paid_to_name',
        'status',
        'paid_to',
        'paid_on',
        'note',
        'created_by',
        'updated_by',
    ];

    /* ===================== Relationships ===================== */

    public function cashTransaction()
    {
        return $this->belongsTo(CashTransaction::class, 'cash_transactions_id');
    }

    public function cashCategory()
    {
        return $this->belongsTo(CashCategory::class, 'cash_category_id');
    }

    public function member()
    {
        return $this->belongsTo(SocietyMember::class, 'society_members_id');
    }
}
