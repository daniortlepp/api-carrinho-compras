<?php

namespace App\Http\Requests;

use App\Domain\Enums\PaymentMethod;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentMethods = array_column(PaymentMethod::cases(), 'value');

        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'numeric', 'min:0.01'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],

            'payment.method' => ['required', 'string', Rule::in($paymentMethods)],
            'payment.installments' => [
                'required_if:payment.method,' . PaymentMethod::CREDIT_CARD_INSTALLMENT->value,
                'integer',
                'min:2',
                'max:12'
            ],

            'payment.card_details' => [
                'required_if:payment.method,' . PaymentMethod::CREDIT_CARD_CASH->value . ',' . PaymentMethod::CREDIT_CARD_INSTALLMENT->value,
                'array'
            ],
            'payment.card_details.holder_name' => ['required_with:payment.card_details', 'string'],
            'payment.card_details.number' => ['required_with:payment.card_details', 'string', 'digits_between:13,16'],
            'payment.card_details.expiry_date' => ['required_with:payment.card_details', 'string', 'date_format:m/y'],
            'payment.card_details.cvv' => ['required_with:payment.card_details', 'string', 'digits:3'],
        ];
    }
    
    /**
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Os dados fornecidos são inválidos.',
            'errors' => $validator->errors(),
        ], 422));
    }
}