<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyRequest extends FormRequest
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
            'name' => 'required',
            'mail' => 'required|email',
            'logo' => 'image|mimes:png,jpg|dimensions:min_width=100,min_height=100',
            'website' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response(
                [
                    'error' => $validator->errors(),
                    'message' => 'Validation Error'
                ],
                422
            )
        );
    }
}
