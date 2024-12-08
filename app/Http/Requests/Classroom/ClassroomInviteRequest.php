<?php

namespace App\Http\Requests\Classroom;

use App\Http\Requests\BaseFormRequest;
use App\Models\User;
use Closure;

class ClassroomInviteRequest extends BaseFormRequest
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
            'students' => ['required', 'array', 'min:1'],
            'students.*' => [
                'required',
                'email',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (! User::query()->where(['email' => $value, 'role' => 'student'])->exists()) {
                        $fail('The selected ' . $attribute . ' is invalid.');
                    }
                }
            ]
        ];
    }
}
