<?php

namespace App\Jobs;

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

        #$url = "https://webhook.site/874d637b-a041-4494-95c4-f0ff75967905";
        $url = route('external.notify');



        $externalNotification = ExternalNotifications::where('payment_id', $payment->id)->first();

        if ($externalNotification) {
            $externalNotification->update([
                'status' => $payment->status,
                'attempts' => $externalNotification->attempts + 1,
                'error' => "",
            ]);
        } else {
            $error= '';
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $body);

            if ($response->failed()) {
                $error= $response->body();
            }

            if($response->requestTimeout()){
                $error= "Request Timeout";
            }

            if($response->tooManyRequests()){
                $error= "Too Many Requests";
            }

            if($response->serverError()){
                $error= "Server Error";
            }

            if($response->clientError()){
                $error= "Client Error";
            }

            ExternalNotifications::create([
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'attempts' => 1,
                    'error' => $error,
                ]);
        }

    }
}
