<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCoeffsRequest extends FormRequest
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
            'coeff_1' => 'required|numeric|min:0|max:1',
            'coeff_2' => 'required|numeric|min:0|max:1',
            'coeff_3' => 'required|numeric|min:0|max:1'
        ];
    }
    public function messages()
    {
        return [
            'coeff_1.min' => 'coeff_1 must be 0 or higher',
            'coeff_1.max' => 'coeff_1 must be less or equal 1',
            'coeff_2.min' => 'coeff_2 must be 0 or higher',
            'coeff_2.max' => 'coeff_2 must be less or equal 1',
            'coeff_3.min' => 'coeff_3 must be 0 or higher',
            'coeff_3.max' => 'coeff_3 must be less or equal 1',
        ];
    }
}
