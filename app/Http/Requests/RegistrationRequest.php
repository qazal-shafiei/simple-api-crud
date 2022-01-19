<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegistrationRequest extends FormRequest
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
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
          'email.required' => 'email is required',
          'email.email'  => 'email is invalid',
          'email.unique' => 'email is taken in use',
          'password.required' => 'password is required',
          'password.confirmed' => 'password confirmation not match'
        ];
    }

    /**
     * @param Validator $validator
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'success'   => false,
         'message'   => 'Validation errors',
         'data'      => $validator->errors()
       ], 400));
    }
}
