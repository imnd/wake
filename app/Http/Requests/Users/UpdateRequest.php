<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $email
 * @property mixed  $password
 */
class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|min:3|max:255',
            'email' => 'sometimes|string|email|max:255',
            'password' => 'sometimes|string|min:6',
        ];
    }
}
