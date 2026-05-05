<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAudit extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_code',
        'action',
        'status',
        'description',
    ];


    public static function log($payment, $action, $status = null, $description = null)
    {
        return self::create([
            'payment_code' => $payment ? $payment->payment_code : $payment,
            'action' => $action,
            'status' => $status,
            'description' => $description
        ]);
    }
}
