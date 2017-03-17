<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Person extends FormRequest
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
            'data'              => 'required',
            'data.*.first_name' => 'required|string',
            'data.*.last_name'  => 'required|string',
            'data.*.age'        => 'required|integer|min:0',
            'data.*.email'      => 'required|email',
            'data.*.secret'     => 'required|string',
        ];
    }
}
