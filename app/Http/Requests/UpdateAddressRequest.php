<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends ApiFormRequest
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
            'address' => [
                'sometimes',
                'required',
            ],
            'district' => [
                'sometimes',
                'required',
                'max:50',
            ],
            'city' => [
                'sometimes',
                'required',
                'max:50',
            ],
            'province' => [
                'sometimes',
                'required',
                'max:50',
            ],
            'postal_code' => [
                'sometimes',
                'required',
                'max:15',
            ],
        ];
    }
}
