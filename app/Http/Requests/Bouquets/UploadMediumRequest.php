<?php

namespace App\Http\Requests\Bouquets;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UploadMediumRequest extends FormRequest
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
                File::types([
                    'video/mpg',
                    'video/mpeg',
                    'video/mp4',
                    'image/jpg',
                    'image/jpeg',
                ])->max(config('media-library.conversions.bouquet.max_size')),
            ]
        ];
    }
}
