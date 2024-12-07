<?php

namespace App\Http\Requests\Me;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class MeAvatarRequest extends BaseFormRequest
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
            'avatar' => [
                'required',
                File::image()
                    ->max(12 * 1024) // 12 MB
                    ->dimensions(
                        Rule::dimensions()
                            ->minWidth(150)
                            ->minHeight(150)
                            ->maxWidth(2000)
                            ->maxHeight(2000)
                    )
            ]
        ];
    }
}
