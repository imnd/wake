<?php

namespace App\Http\Requests\Memorials;

use App\Models\Memorial;
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
                    Memorial::STATUS_PUBLISHED,
                    Memorial::STATUS_ARCHIVED,
                    Memorial::STATUS_DELETED,
                ]),
            ],
        ];
    }
}
