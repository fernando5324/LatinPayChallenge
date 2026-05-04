<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payments;
use Illuminate\Support\Str;

class SystemFunctions
{
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