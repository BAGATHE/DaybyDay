<?php

namespace App\Http\Requests\Payment;

use App\Enums\PaymentSource;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('payment-create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'source' => ['required', PaymentSource::validationRules()],
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'amount.numeric' => __('The amount must be a valid number.'),
            'amount.required' => __('The amount is required.'),
            'amount.min' => __('The amount must be greater than 0.'),
            'payment_date.date'  => __('The payment date is not a valid date.'),
            'payment_date.required'  => __('The payment date is required.'),
            'source.required' => __('The source is required.'),
            'source.in' => __('Invalid source'),
        ];
    }

}
