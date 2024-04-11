<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UploadAvatarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => [
                'file',
                'required',
                File::types(['jpeg', 'jpg'])->max(config('media-library.avatar.max_size')),
            ],
        ];
    }
}
