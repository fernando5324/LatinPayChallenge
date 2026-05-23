<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankReconciliationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            /** @example BANK_A */
            'bank' => ['required', 'string'],
            /** @example 2026-04-24 */
            'process_date' => ['required', 'date_format:Y-m-d'],
            /** @example  [{"bank_movement_id": "mov_001", "bank_transaction_id": "bank_tx_999", "payment_code": "LTP-20260503-000001", "amount": 150.50, "currency": "PEN", "paid_at": "2026-04-24 20:44:30"}, {"bank_movement_id": "mov_002", "bank_transaction_id": "bank_tx_1000", "payment_code": "LTP-20260504-000002", "amount": 200.00, "currency": "PEN", "paid_at": "2026-04-24 20:46:00"}] */
            'movements' => ['required', 'array'],
            'movements.*.bank_movement_id' => ['required', 'string'],
            'movements.*.bank_transaction_id' => ['required', 'string'],
            'movements.*.payment_code' => ['required', 'string'],
            'movements.*.amount' => ['required', 'numeric'],
            'movements.*.currency' => ['required', 'string', 'in:PEN,USD'],
            'movements.*.paid_at' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
