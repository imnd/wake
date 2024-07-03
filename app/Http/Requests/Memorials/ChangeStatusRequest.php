<?php

namespace App\Http\Requests\Memorials;

use App\Helpers\Statuses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $status
 */
class ChangeStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    Statuses::STATUS_PAID,
                    Statuses::STATUS_UNPAID,
                    Statuses::STATUS_ARCHIVED,
                    Statuses::STATUS_DELETED,
                ]),
            ],
        ];
    }
}
