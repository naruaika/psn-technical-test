<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends ApiFormRequest
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
        $id = $this->route('customer')->id;

        return [
            'title' => [
                'sometimes',
                'nullable',
                'min:2',
                'max:30'
            ],
            'name' => [
                'sometimes',
                'nullable',
                'required',
                'max:255'
            ],
            'gender' => [
                'sometimes',
                'nullable',
                'in:M,F'
            ],
            'phone_number' => [
                'sometimes',
                'required',
                'string',
                'max:15',
                'unique:customers,phone_number,'.$id.',id'
            ],
            'avatar' => [
                'sometimes',
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/jpg',
                'max:512'
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                'unique:customers,email,'.$id.',id'
            ],
        ];
    }
}
