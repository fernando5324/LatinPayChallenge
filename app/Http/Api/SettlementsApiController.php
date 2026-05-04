<?php

namespace App\Http\Api;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payments;
use Carbon\Carbon;

class SettlementsApiController extends Controller
{
    public function settlementsCandidates(Request $request)
    {

        try {

            $payments = Payments::query()->where('status', 'PAID')->get(); #Evito OBSERVED y PENDING

            $result = $payments->map(function ($payment) {

                if (!$payment->paid_at) {
                    return null;
                }

                $paidAt = Carbon::parse($payment->paid_at)->timezone(config("app.timezone"));

                $cutoff = $paidAt->copy()->setTime(20, 45);

                if ($paidAt->lte($cutoff)) {
                    $settlementDate = $paidAt->copy()->addDay()->toDateString();
                    $cutoffMessage = 'Se encuentra dentro del rango horario (<= 20:45)';
                } else {
                    $settlementDate = $paidAt->copy()->addDays(2)->toDateString();
                    $cutoffMessage = 'Se encuentra fuera del rango horario (> 20:45)';
                }

                return [
                    'payment_code' => $payment->payment_code,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'paid_at' => $paidAt->toDateTimeString(),
                    'settlement_date' => $settlementDate,
                    'cutoff_message' => $cutoffMessage,
                ];
            })->filter()->values();

            return response()->json($this->getDataResponseSuccess($result), 200);

        } catch (\Exception $e) {
            return response()->json($this->getDataResponseFail($e), 500);
        }
    }
}