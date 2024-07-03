<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ConditionalRules;
use Illuminate\Validation\Rule;
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
                    'video/quicktime',
                    'image/jpg',
                    'image/jpeg',
                ]),
                $this->videoSizeRule(),
                $this->imageSizeRule(),
            ]
        ];
    }

    protected function videoSizeRule(): ConditionalRules
    {
        return Rule::when(function ($input) {
            return in_array($input->file->getMimeType(), [
                'video/mpg',
                'video/mpeg',
                'video/mp4',
                'video/quicktime',
            ]);
        }, 'max:' . config('media-library.conversions.video_max_size'));
    }

    protected function imageSizeRule(): ConditionalRules
    {
        return Rule::when(function ($input) {
            return in_array($input->file->getMimeType(), [
                'image/jpg',
                'image/jpeg',
            ]);
        }, 'max:' . config('media-library.conversions.image_max_size'));
    }
}
