<?php

namespace App\Http\Requests\Memorials;

use App\Models\Memorial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $title
 * @property string $text
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $gender
 * @property string $place_of_birth
 * @property string $place_of_death
 * @property string $day_of_birth
 * @property string $day_of_death
 * @property bool   $default
 */
class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'sometimes|string',
            'text' => 'sometimes|string',
            'first_name' => 'sometimes|string',
            'middle_name' => 'present',
            'last_name' => 'sometimes|string',
            'gender' => [
                'sometimes',
                'string',
                Rule::in([Memorial::GENDER_MALE, Memorial::GENDER_FEMALE, Memorial::GENDER_OTHER]),
            ],
            'goal_sum' => 'sometimes|numeric',
            'place_of_birth' => 'sometimes|string',
            'place_of_death' => 'sometimes|string',
            'day_of_birth' => 'sometimes|date',
            'day_of_death' => 'sometimes|date|after:day_of_birth',
            'default' => 'sometimes|boolean',
        ];
    }
}
