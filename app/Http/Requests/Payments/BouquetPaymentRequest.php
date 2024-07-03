<?php

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property float $amount
 * @property int   $bouquet_id
 */
class BouquetPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.5'],
            'bouquet_id' => ['required', 'numeric'],
        ];
    }
}
