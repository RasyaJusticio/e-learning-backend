<?php

namespace App\Http\Requests\Me;

use App\Http\Requests\BaseFormRequest;
use Closure;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class MeUpdateRequest extends BaseFormRequest
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
            'name' => ['nullable', 'string', 'min:3', 'max:30'],
            'username' => [
                'nullable',
                'string',
                'min:3',
                'max:15',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value === null || trim($value) === '') {
                        return;
                    }

                    $value = strtolower($value);

                    if ($value[0] == '-' || $value[strlen($value) - 1] == '-') {
                        $fail("The {$attribute} cannot start or end with a hyphen.");
                    }
                    if ($value[0] == '_' || $value[strlen($value) - 1] == '_') {
                        $fail("The {$attribute} cannot start or end with an underscore.");
                    }

                    if (!preg_match('/^[\p{L}\p{N}_-]+$/u', $value)) {
                        $fail("The {$attribute} can only contain letters, numbers, underscores, and hyphens.");
                    }
                },
                'unique:users,username',
            ],
        ];
    }
}
