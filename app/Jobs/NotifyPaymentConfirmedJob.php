<?php

namespace App\Jobs;

use App\Enums\AuditStatus;
use App\Models\PaymentAudit;
use App\Models\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\ExternalNotifications;
class NotifyPaymentConfirmedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Payments $payment;
    /**
     * Create a new job instance.
     */
    public function __construct(Payments $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment = $this->payment;

        $body = [
            'payment_code' => $payment->payment_code,
            'status' => $payment->status,
            'paid_at' => $payment->paid_at,
        ];

        $url = route('external.notify');

        $externalNotification = ExternalNotifications::where('payment_id', $payment->id)->first();

        if ($externalNotification && $externalNotification->status === 'SUCCESS') {
            return;
        }

        $error = null;

        try {

            $response = Http::timeout(5)
                ->acceptJson()
                ->post($url, $body);

            if ($response->successful()) {
                $status = 'SUCCESS';
            } else {
                $status = 'FAILED';
                $error = $response->body();
            }

        } catch (\Exception $e) {
            $status = 'FAILED';
            $error = $e->getMessage();
        }

        PaymentAudit::log($payment,AuditStatus::EXTERNAL_NOTIFICATION, $status, $error);

        ExternalNotifications::updateOrCreate(
            ['payment_id' => $payment->id],
            [
                'status' => $status,
                'attempts' => ($externalNotification->attempts ?? 0) + 1,
                'error' => $error
            ]
        );
    }
}
