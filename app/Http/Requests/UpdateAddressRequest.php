<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
        Log::warning('Trying to update a address.', [
            'address' => $this->route('address')->id,
            'input' => $this->input(),
        ]);

        return [
            'address' => [
                'sometimes',
                'required',
                'max:255',
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
                'regex:/^([0-9]*)$/i',
                'max:15',
            ],
        ];
    }
}
