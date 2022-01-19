<?php

namespace App\Http\Requests\Order;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateRequest extends FormRequest
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
            "quantity" => 'required',
            "amount" => 'required'
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            "quantity.required" => "the quantity field is required.",
            "amount.required" => "the amount field is required.",
        ];
    }

    /**
     * @param Validator $validator
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
           'succsess' => false,
           'message' => 'validations error',
           'data' => $validator->errors()
        ], 400));
    }
}
