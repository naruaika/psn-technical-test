<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends ApiFormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => [
                'nullable',
                'min:2',
                'max:30'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'gender' => [
                'nullable',
                'in:M,F'
            ],
            'phone_number' => [
                'required',
                'string',
                'max:15',
                'unique:customers'
            ],
            'avatar' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/jpg',
                'max:512'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:customers'
            ],
        ];
    }
}
