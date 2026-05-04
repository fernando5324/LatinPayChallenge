<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\PaymentStatus;

class BankNotifications extends Model
{
    protected $table = 'bank_notifications';

    #protected $connection = 'bd-name';

    protected $fillable = [
        'event_id',
        'bank_transaction_id',
        'payment_code',
        'payload',
        'amount',
        'currency',
        'status',
        'description',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
        'payload' => 'json',
    ];

    public $timestamps = true;

    public static function validateRequest($request)
    {
        $rules = [
            'event_id' => 'required',
            'bank_transaction_id' => 'required',
            'payment_code' => 'required',
            #'payload' => 'required |json',
            'amount' => 'required|numeric|decimal:1,2',
            'currency' => 'required|in:PEN,USD',
            'status' => 'required',
        ];


        $validate = $request->validate($rules);

        return $validate;
    }


    public static function generatePaymentCode()
    {
        $date = now()->format('Ymd');
        $prefix = 'LTP';

        $sequence = Payments::count();
        return $prefix . '-' . $date . '-' . str_pad($sequence + 1, 6, '0', STR_PAD_LEFT);

    }


}
