<?php

namespace App\Http\Requests\Challenges;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateChallengeRequest extends FormRequest
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
            'terms_accepted' => ['required', Rule::in(['true'])],
            'title' => ['bail', 'required', 'string', 'max:255', 'min:3'],
            'description' => ['bail', 'required', 'max:500', 'min:200'],
            'start_time' => ['required', 'date_format:m-d-Y h:m A'],
            'duration_days' => ['required', 'integer'],
            'duration_hours' => ['required', 'integer'],
            'duration_minutes' => ['required', 'integer'],
            'file' => ['required', 'mimes:jpg,jpeg,png,mp4,webm'],
            'location' => ['required', 'string'],
            'amount' => ['required', 'integer'],
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
            'terms_accepted.in' => 'Terms & conditions must be accepted to create a new challenge.'
        ];
    }
}
