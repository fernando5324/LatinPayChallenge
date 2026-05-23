<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\PaymentStatus;

class Payments extends Model
{
    protected $table = 'payments';

    #protected $connection = 'bd-name';

    protected $fillable = [
        'id',
        'payment_code',
        'merchant_id',
        'customer_document',
        'amount',
        'currency',
        'status',
        'description',
        'paid_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'status' => PaymentStatus::class,
    ];

    public $timestamps = false;


    public static function generatePaymentCode()
    {
        $date = now()->format('Ymd');
        $prefix = 'LTP';

        #$item = Payments::where('payment_code', 'LIKE', $date . '%')->orderBy('payment_code', 'desc')->value("payment_code");
        $sequence = Payments::count();
        #$item = null;
        #if ($sequence) {
        return $prefix . '-' . $date . '-' . str_pad($sequence + 1, 6, '0', STR_PAD_LEFT);
        #}


        #return $date . Str::random(20);
    }

}
