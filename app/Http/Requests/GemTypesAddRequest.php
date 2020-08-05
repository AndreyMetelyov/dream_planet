<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GemTypesAddRequest extends FormRequest
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
            'type' => 'required|min:3|max:10',
            // 'koef' => 'required|numeric|min:0|max:5'
        ];
    }
    public function messages()
    {
        return [
            //'koef.min' => 'jems count must be 0 or higher',
            //'koef.max' => 'jems count must be less or equal 5'

        ];
    }
}
