<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->phone_number)) {
            $this->merge([
                'phone_number' => normalise_phone_number($this->phone_number),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        Log::warning('Trying to update a customer.', [
            'customer' => $this->route('customer')->id,
            'input' => $this->input(),
        ]);

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
                'regex:/^([0-9\s\-\+\(\)]*)$/i',
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
                'unique:customers,email,'.$this->route('customer')->id.',id'
            ],
        ];
    }
}
