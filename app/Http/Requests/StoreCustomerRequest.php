<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
        Log::warning('Trying to create a new customer.', [
            'input' => $this->input()
        ]);

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
                'regex:/^([0-9\s\-\+\(\)]*)$/i',
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
