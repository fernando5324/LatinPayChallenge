<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePaymentRequest extends FormRequest
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
            /** @example 10 */
            'merchant_id' => ['required', 'integer'],
            /** @example 76359665 */
            'customer_document' => ['required', 'string'],
            /** @example 150.50 */
            'amount' => ['required', 'numeric', 'decimal:1,2'],
            /** @example PEN */
            'currency' => ['required', 'string', 'in:PEN,USD'],
            /** @example Pago mensual */
            'description' => ['nullable', 'string', 'max:400'],
        ];
    }


}
