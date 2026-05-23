<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankNotificationRequest extends FormRequest
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
            /** @example 123e4567-e89b-12d3-a456-426614174000 */
            'event_id' => ['required', 'string'],
            /** @example bank_tx_999 */
            'bank_transaction_id' => ['required', 'string'],
            /** @example LTP-20260504-000002 */
            'payment_code' => ['required', 'string'],
            /** @example 2026-04-24 20:46:00 */
            'paid_at' => ['required', 'date_format:Y-m-d H:i:s'],
            /** @example 200.00 */
            'amount' => ['required', 'numeric', 'decimal:1,2'],
            /** @example PEN */
            'currency' => ['required', 'string', 'in:PEN,USD'],
            /** @example SUCCESS */
            'status' => ['required', 'string', 'in:PENDING,PAID,CANCELLED,OBSERVED,UNMATCHED,RECONCILED,LATE_CONFIRMATION,INCONSISTENCY'],
            
        ];
    }
}
