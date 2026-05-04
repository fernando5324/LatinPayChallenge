<?php

namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payments;
use App\Enums\PaymentStatus;
use App\Enums\AuditStatus;

class PaymentApiController extends Controller
{
    public function save(Request $request)
    {
        try {

            $code = Payments::generatePaymentCode();

            Payments::validateRequest($request);

            $payment = Payments::create([
                'payment_code' => $request->get('payment_code') ?? $code,
                'merchant_id' => $request->get('merchant_id'),
                'customer_document' => $request->get('customer_document'),
                'amount' => $request->get('amount'),
                'currency' => $request->get('currency'),
                'status' => PaymentStatus::PENDING,
                'description' => $request->get('description'),
            ]);

            PaymentAudit::log($payment,AuditStatus::CREATED,$payment->status,'');

            return response()->json($this->getDataResponseSuccess($payment), 200);
        } catch (\Exception $e) {
            return response()->json($this->getDataResponseFail($e), 500);
        }
    }
}