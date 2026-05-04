<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAudit extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id',
        'action',
        'status',
        'description',
    ];


    public static function log($payment, $action, $status = null, $description = null)
    {
        return self::create([
            'payment_id' => $payment->id,
            'action' => $action,
            'status' => $status,
            'description' => $description
        ]);
    }
}
