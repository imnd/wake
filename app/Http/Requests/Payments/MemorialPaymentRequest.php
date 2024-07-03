<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property float $amount
 * @property int   $memorial_id
 */
class MemorialPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.5'],
            'memorial_id' => ['required', 'numeric'],
        ];
    }
}
