<?php

namespace App\Http\Requests\Bouquets;

use App\Helpers\Statuses;
use App\Models\BouquetType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property bool   $anonymous
 * @property string $condolences
 * @property string $from
 * @property string $status
 * @property string $type_id
 */
class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'anonymous' => 'boolean',
            'condolences' => 'sometimes|string|min:3|max:16484',
            'from' => 'sometimes|string|min:3|max:64',
            'status' => [
                'sometimes',
                'string',
                Rule::in([Statuses::STATUS_PAID, Statuses::STATUS_UNPAID]),
            ],
            'type_id' => [
                'sometimes',
                'numeric',
                Rule::in(BouquetType::all()->pluck('id')),
            ],
        ];
    }
}
