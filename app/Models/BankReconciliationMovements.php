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

    public static function validateRequest($request)
    {
        $rules = [
            "movements"=>"required",
            "bank"=>"required",
            "process_date"=>"required|date_format:Y-m-d",
            
        ];

        $messages = [
            "bank.required"=>"Banco requerido",
            "process_date.required"=>"Fecha de proceso requerida",
            "process_date.date_format"=>"Fecha de proceso invalida",
            "movements.required"=>"Movimientos requeridos",
        ];

        $validate = $request->validate($rules, $messages);

        return $validate;
    }

}
