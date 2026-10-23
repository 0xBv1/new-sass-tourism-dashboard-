<?php

namespace App\Http\Requests\Api\Profile;

use App\Traits\Response\RequestValidationErrorResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ChangeProfileImageRequest extends FormRequest
{
    use RequestValidationErrorResponse;
    public function rules(): array
    {
        return [
            'image' => ['required', 'image', File::image()->max(2 * 1024)]
        ];
    }
}
