<?php

namespace App\Http\Requests\Challenges;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'description' => ['bail', 'required', 'max:500'],
            'result_type' => ['bail', 'required'],
            'start_time' => ['required', 'date_format:Y-m-d h:i', 'after:'.date(DATE_ATOM, time() + (5 * 60 * 60))],
            'duration_days' => ['required', 'integer', 'min:0'],
            'duration_hours' => ['required', 'integer', 'min:0', 'max:24'],
            'duration_minutes' => ['required', 'integer', 'min:0', 'max:60'],
            'location' => ['required', 'string', 'max:75'],
            'amount' => ['required', 'numeric', 'min:1'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
        switch ($this->method()) {
            case 'POST': {
                $rules['title'] = ['bail', 'required', 'unique:challenges', 'string', 'max:75', 'min:3'];
                $rules['terms_accepted'] = ['required', Rule::in(['true'])];
                $rules['file'] = ['required', 'mimes:jpg,jpeg,png,mp4,webm', 'max:51200‬'];
                break;
            }
            case 'PUT' || 'PATCH': {
                $rules['title'] = ['bail', 'required', 'string', 'max:75', 'min:3'];
                $rules['file'] = ['mimes:jpg,jpeg,png,mp4,webm', 'max:51200‬'];
                break;
            }
            default:
                break;
        }
        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.unique' => 'Challenge with the provided title already exists.',
            'start_time.after' => 'Challenge start time must be greater than the current time.',
            'category_id.exists' => 'Kindly select a category of your challenge.',
        ];
    }

}
