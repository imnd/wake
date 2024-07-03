<?php

namespace App\Http\Requests\Media;

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
                File::types(['jpeg', 'jpg'])->max(config('media-library.conversions.image_max_size')),
            ],
        ];
    }
}
