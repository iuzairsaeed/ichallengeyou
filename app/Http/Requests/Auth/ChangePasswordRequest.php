<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MatchOldPassword;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => ['required', new MatchOldPassword],
            'password' => ['required', 'string', 'min:8', 'regex:/[@$!%*#?&]/', 'confirmed'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.regex' => 'Password must contain at least one special character.'
        ];
    }

    /**
     * Custom response
     *
     * @return array
     */

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response(['message' => $validator->errors()->first()], 400);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
