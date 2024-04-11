<?php

namespace App\Http\Requests\Memorials;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UploadMediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'files' => 'required|array',
            'files.*' => [
                'file',
                'required',
                File::types([
                    'video/mpg',
                    'video/mpeg',
                    'video/mp4',
                    'image/jpg',
                    'image/jpeg',
                ])->max(config('media-library.conversions.memorial.max_size')),
            ]
        ];
    }
}
