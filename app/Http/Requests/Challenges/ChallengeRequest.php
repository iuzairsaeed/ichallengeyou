<?php

namespace App\Http\Requests\Challenges;

use Illuminate\Foundation\Http\FormRequest;

class ChallengeRequest extends FormRequest
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
        $rules = [
            'title' => ['bail', 'required', 'string', 'max:255', 'min:3'],
            'description' => ['bail', 'required', 'max:500', 'min:200'],
            'start_time' => ['required', 'date_format:m-d-Y h:m A'],
            'duration_days' => ['required', 'integer', 'min:0'],
            'duration_hours' => ['required', 'integer', 'min:0', 'max:23'],
            'duration_minutes' => ['required', 'integer', 'min:0', 'max:59'],
            'location' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
            'file' => ['required', 'mimes:jpg,jpeg,png,mp4,webm', 'max:20480'],
        ];
        switch ($this->method()) {
            case 'POST': {
                $rules['terms_accepted'] = ['required', Rule::in(['true'])];
                $rules['file'] = ['required', 'mimes:jpg,jpeg,png,mp4,webm'];
            }
            case 'PUT' || 'PATCH': {
                $rules['file'] = ['mimes:jpg,jpeg,png,mp4,webm', 'max:20480'];
            }
            default:
                break;
        }
        return $rules;
    }
}
