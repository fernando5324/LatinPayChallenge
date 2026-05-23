<?php

namespace App\Http\Api;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyPaymentConfirmedJob;
use App\Models\BankReconciliationMovements;
use App\Models\Payments;
use Illuminate\Http\Request;
use App\Models\BankNotifications;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessBankNotificationJob;

class BankNotificationApiController extends Controller
{

    /**
     * Notificar al banco

     */

    public function notify(Request $request)
    {
        DB::beginTransaction();
        try {
            #Al ser solo un registro a guardar no es necesario pasarlo a jobs a menos que esté saturado
            BankNotifications::validateRequest($request);
            $bankNotification = new BankNotifications();
            $bankNotification->event_id = $request->get('event_id') ?? null;
            $bankNotification->bank_transaction_id = $request->get('bank_transaction_id') ?? null;
            $bankNotification->payment_code = $request->get('payment_code') ?? null;
            $bankNotification->payload = $request->all(); # json_encode((object)$request->all());
            $bankNotification->amount = $request->get('amount') ?? null;
            $bankNotification->currency = $request->get('currency') ?? null;
            $bankNotification->status = $request->get('status') ?? null;
            $bankNotification->paid_at = $request->get('paid_at') ?? null;
            $bankNotification->save();

            ProcessBankNotificationJob::dispatch($bankNotification->id);

            DB::commit();

            return response()->json($this->getDataResponseSuccess($bankNotification), 200);
        } catch (QueryException $e) {

            DB::rollBack();
            if ($e->errorInfo[1] == 1062) { #1062 codigo de error de Mysql para registros duplicados
                #No se concidera error por lo tanto enviare codigo 200 para que no vuelvan a intentar
                return response()->json($this->getDataResponseSuccess('Ya se ha registrado una notificación con este ID de evento'), 200);
            }

            return response()->json($this->getDataResponseFail($e), 500);
        } catch (\Throwable $th) {

            #logger($th->getMessage());
            return response()->json($this->getDataResponseFail($th), 500);
        }
    }


    /**
     * Reconciliación de pagos
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function reconciliation(Request $request)
    {

        DB::beginTransaction();
        try {
            BankReconciliationMovements::validateRequest($request);
            $bankMovements = BankReconciliationMovements::pluck('bank_movement_id')->toArray();
            $payments = Payments::select('payment_code', 'amount', 'currency', 'status')->get();
            foreach ($request->get("movements") as $item) {
                $bankMovementId = $item['bank_movement_id'] ?? null;

                if (in_array($bankMovementId, $bankMovements)) {
                    continue;
                }

                $payment = $payments->where('payment_code', $item['payment_code'])->first();
                $status = PaymentStatus::RECONCILED;

                if ($payment) {

                    if ($payment->status == PaymentStatus::PENDING) {
                        $status = PaymentStatus::LATE_CONFIRMATION;

                        $payment->status = PaymentStatus::PAID;
                        $payment->paid_at = $item['paid_at'] ?? null;
                        $payment->save();

                        NotifyPaymentConfirmedJob::dispatch($payment);
                    }

                    if ($payment->amount != $item['amount'] || $payment->currency != $item['currency']) {
                        $status = PaymentStatus::INCONSISTENCY;
                    }
                } else {
                    $status = PaymentStatus::UNMATCHED;
                }

                $bankReconciliationMovement = new BankReconciliationMovements();
                $bankReconciliationMovement->bank = $request->get("bank") ?? null;
                $bankReconciliationMovement->bank_transaction_id = $item['bank_transaction_id'] ?? null;
                $bankReconciliationMovement->bank_movement_id = $bankMovementId;
                $bankReconciliationMovement->process_date = $request->get('process_date') ?? null;
                $bankReconciliationMovement->status = $status;
                $bankReconciliationMovement->payment_code = $item['payment_code'] ?? null;
                $bankReconciliationMovement->amount = $item['amount'] ?? null;
                $bankReconciliationMovement->currency = $item['currency'] ?? null;
                $bankReconciliationMovement->save();
            }


            DB::commit();
            return response()->json($this->getDataResponseSuccess($bankMovements), 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json($this->getDataResponseFail($e), 500);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($this->getDataResponseFail($th), 500);
        }
    }
}
