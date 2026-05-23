<?php

namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payments;
use App\Enums\PaymentStatus;
use App\Enums\AuditStatus;
use App\Enums\Messages;
use App\Http\Requests\SavePaymentRequest;
use Illuminate\Http\JsonResponse;

class PaymentApiController extends Controller
{
/**
 * Crear operación de pago
 */

    public function save(SavePaymentRequest  $request): JsonResponse
    {
        try {

            $code = Payments::generatePaymentCode();

            $payment = Payments::create([
                'payment_code' => $request->get('payment_code') ?? $code,
                'merchant_id' => $request->get('merchant_id'),
                'customer_document' => $request->get('customer_document'),
                'amount' => $request->get('amount'),
                'currency' => $request->get('currency'),
                'status' => PaymentStatus::PENDING,
                'description' => $request->get('description'),
            ]);

            PaymentAudit::log($payment, AuditStatus::CREATED, $payment->status, '');

            return response()->json($this->getDataResponse(true, Messages::CREATE_SUCCESS, $payment), 200);
        } catch (\Exception $e) {
            return response()->json($this->getDataResponseFail($e), 500);
        }
    }
}
