<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends ApiFormRequest
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
            'customer_uuid' => [
                'required',
                'exists:customers,uuid',
            ],
            'address' => [
                'required',
            ],
            'district' => [
                'required',
                'max:50',
            ],
            'city' => [
                'required',
                'max:50',
            ],
            'province' => [
                'required',
                'max:50',
            ],
            'postal_code' => [
                'required',
                'max:15',
            ],
        ];
    }
}
