<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['required', 'string', 'max:25'],
            'city' => ['required', 'string', 'max:60'],
            'district' => ['required', 'string', 'max:60'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'note' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', Rule::in(['kredi_karti', 'havale', 'kapida_odeme'])],
            'card_name' => ['required_if:payment_method,kredi_karti', 'nullable', 'string', 'max:120'],
            'card_number' => ['required_if:payment_method,kredi_karti', 'nullable', 'string', 'regex:/^[0-9 ]{16,23}$/'],
            'card_expiry' => ['required_if:payment_method,kredi_karti', 'nullable', 'string', 'regex:#^(0[1-9]|1[0-2])/[0-9]{2}$#'],
            'card_cvv' => ['required_if:payment_method,kredi_karti', 'nullable', 'digits_between:3,4'],
            'terms' => ['accepted'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return [
            'customer_name' => __('fields.name'),
            'email' => __('fields.email'),
            'phone' => __('fields.phone'),
            'city' => __('fields.city'),
            'district' => __('fields.district'),
            'address' => __('fields.address'),
            'note' => __('fields.note'),
            'payment_method' => __('fields.payment_method'),
            'card_name' => __('fields.card_name'),
            'card_number' => __('fields.card_number'),
            'card_expiry' => __('fields.card_expiry'),
            'card_cvv' => __('fields.card_cvv'),
            'terms' => __('fields.terms'),
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'terms.accepted' => __('checkout_messages.terms'),
            'card_number.regex' => __('checkout_messages.card_number'),
            'card_expiry.regex' => __('checkout_messages.card_expiry'),
        ];
    }
}
