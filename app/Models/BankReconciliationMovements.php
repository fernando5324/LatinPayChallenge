<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class BankReconciliationMovements extends Model
{
    protected $table = 'bank_reconciliation_movements';

    #protected $connection = 'bd-name';

    protected $fillable = [
        'bank',
        'bank_transaction_id',
        'bank_movement_id',
        'process_date',
        'status',
        'payment_code',
        'amount',
        'currency'
    ];

    protected $casts = [
        'process_date' => 'date',
        'status' => PaymentStatus::class,
    ];

    public $timestamps = false;

}
