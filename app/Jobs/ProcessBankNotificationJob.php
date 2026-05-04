<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Helpers\SystemConfiguration;
use App\Models\BankNotifications;
use App\Models\Entity;
use App\Models\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBankNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    #public $data;
    #public $file;
    public int $notificationId;

    /**
     * Create a new job instance.
     */
    public function __construct($notificationId)
    {
        $this->notificationId = $notificationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notification = BankNotifications::find($this->notificationId);

        if (empty($notification)) {
            logger("Notificación no encontrada: " . $this->notificationId);
            return;
        }

        $payment = Payments::where('payment_code', $notification->payment_code)->first();

        if ($payment == null || $payment->status != PaymentStatus::PENDING) {
            logger("Pago no encontrado o no está en estado PENDING: " . $payment->payment_code);
            return;
        }

        if ($payment->amount != $notification->amount || $payment->currency != $notification->currency) {
            $payment->update([
                "status" => PaymentStatus::OBSERVED,
                "updated_at" => now()
            ]);

            logger("Pago observado: " . $payment->payment_code);

            return;
        }

        $payment->update([
            "status" => PaymentStatus::PAID,
            "paid_at" => $notification->paid_at,
            "updated_at" => now()
        ]);

        NotifyPaymentConfirmedJob::dispatch($payment);

    }
}
