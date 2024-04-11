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
 * @property bool $default
 */
class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string',
            'text' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'sometimes|string',
            'last_name' => 'required|string',
            'gender' => [
                'sometimes',
                'string',
                Rule::in([Memorial::GENDER_MALE, Memorial::GENDER_FEMALE, Memorial::GENDER_OTHER]),
            ],
            'place_of_birth' => 'required|string',
            'place_of_death' => 'required|string',
            'day_of_birth' => 'required|date',
            'day_of_death' => 'required|date',
            'default' => 'sometimes|boolean',
        ];
    }
}
