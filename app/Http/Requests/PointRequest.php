<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class PointRequest extends FormRequest
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
        if( $this->route('id') )
            $nameRules = ['required','string','max:20', Rule::unique('points')->ignore( $this->route('id') )];
        else 
            $nameRules = ['required','string','max:20'];
        
        //Regex: evaluates if a coordinate value has 1-4 integer digits (positive or negatives ones) and 0-2 decimals digits.
        return [
            'name' => $nameRules,
            'coordinate_x' => 'required|numeric|regex:/^-?\d{1,4}(\.\d{1,2})?$/',
            'coordinate_y' => 'required|numeric|regex:/^-?\d{1,4}(\.\d{1,2})?$/'
        ];
    }

    public function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Point can not be saved.','errors' => array($validator->errors())], 422)); 
    }
}
