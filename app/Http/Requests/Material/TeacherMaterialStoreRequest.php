<?php

namespace App\Http\Requests\Material;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rules\File;

class TeacherMaterialStoreRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:50'],
            'content' => ['required', 'string', 'max:5000'],
            'files' => ['nullable', 'array', 'min:1'],
            'files.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (in_array($extension, ['mp4', 'mkv', 'avi'])) {
                        if ($value->getSize() >= 250 * 1024 * 1024) {
                            $fail('The selected ' . $attribute . ' must not be greater than 250 MB.');
                        }
                        return true;
                    }

                    if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'docx', 'pptx', 'xlsx'])) {
                        if ($value->getSize() >= 10 * 1024 * 1024) {
                            $fail('The selected ' . $attribute . ' must not be greater than 10 MB.');
                        }
                        return true;
                    }

                    $fail('The selected ' . $attribute . ' is invalid.');
                },
            ],
        ];
    }
}
