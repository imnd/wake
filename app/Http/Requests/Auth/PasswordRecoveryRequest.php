<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $token
 * @property string $email
 * @property string $password
 */
class PasswordRecoveryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ];
    }
}
