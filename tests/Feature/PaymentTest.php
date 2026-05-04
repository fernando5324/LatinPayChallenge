<?php
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Jobs\NotifyPaymentConfirmedJob;
use App\Models\Payments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\CheckApiToken;


class PaymentTest extends TestCase
{

    use RefreshDatabase;

    public function test_create_payment_pending()
    {
        $this->withoutMiddleware([
            CheckApiToken::class
        ]);

        $response = $this->postJson('/api/v1/payments', [
            'merchant_id' => 1,
            'customer_document' => '12345678',
            'amount' => 150.51,
            'currency' => 'PEN',
            'description' => 'Test payment'
        ]);


        $response->assertStatus(200);

        $this->assertDatabaseHas('payments', [
            'status' => 'PENDING',
            'amount' => 150.51
        ]);

        return $response;
    }

    public function test_prevent_duplicate_event()
    {
        $this->withoutMiddleware([
            CheckApiToken::class
        ]);

        Payments::create([
            'payment_code' => 'LTP-20260504-000010',
            'amount' => 150.51,
            'currency' => 'PEN',
            'status' => 'PENDING'
        ]);

        $payload = [
            'event_id' => 'evt_1',
            'bank_transaction_id' => 'tx_1',
            'payment_code' => 'LTP-20260504-000010',
            'amount' => 150.51,
            'currency' => 'PEN',
            'status' => 'PAID',
            'paid_at' => now()->toDateTimeString(),
        ];

        $this->postJson('/api/v1/bank/notifications', $payload);
        $this->postJson('/api/v1/bank/notifications', $payload);

        $this->assertDatabaseCount('bank_notifications', 1);
    }

    public function test_mark_observed_when_amount_is_wrong()
    {
        config(['queue.default' => 'sync']); // 🔥 CLAVE

        $this->withoutMiddleware([
            CheckApiToken::class
        ]);

        Payments::create([
            'payment_code' => 'LTP-20260504-000020',
            'amount' => 150.51,
            'currency' => 'PEN',
            'status' => 'PENDING'
        ]);

        $this->postJson('/api/v1/bank/notifications', [
            'event_id' => 'evt_0012',
            'bank_transaction_id' => 'tx_2',
            'payment_code' => 'LTP-20260504-000020',
            'amount' => 200,
            'currency' => 'PEN',
            'status' => 'PAID',
            'paid_at' => "2026-04-24 20:44:00"
        ]);

        $this->assertDatabaseHas('payments', [
            'payment_code' => 'LTP-20260504-000020',
            'status' => 'OBSERVED'
        ]);
    }

}