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

    public static function validateRequest($request)
    {
        $rules = [
            #'payment_code' => 'required',
            'merchant_id' => 'required',
            'customer_document' => 'required',
            'amount' => 'required | numeric | decimal:1,2',
            'currency' => 'required | in:PEN,USD ',
            #'status' => 'required',
           'description' => 'nullable|string',
            #'created_at' => 'required',
            #'updated_at' => 'required'
        ];

        $messages = [
            #'payment_code.required' => 'El código de pago es requerido',
            'merchant_id.required' => 'El merchant_id es requerido',
            'customer_document.required' => 'El customer_document es requerido',
            'amount.required' => 'El amount es requerido',
            'currency.required' => 'La currency es requerida',
            #'status.required' => 'El status es requerido',
            'description.required' => 'La description es requerida',
            #'created_at.required' => 'El created_at es requerido',
            #'updated_at.required' => 'El updated_at es requerido'
        ];

        $validate = $request->validate($rules, $messages);

        return $validate;
    }


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
