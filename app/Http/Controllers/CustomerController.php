<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CustomerResource::collection(Customer::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        // Get the validated input data
        $input = $request->safe()->only([
            'title',
            'name',
            'gender',
            'phone_number',
            'avatar',
            'email',
        ]);

        if (isset($input['avatar'])) {
            // Store the customer's avatar
            $input['avatar'] = $input['avatar']->storePublicly('public/avatars');
        }

        // Insert the newly customer record
        $customer = Customer::create($input);

        return new CustomerResource($customer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        // Get the validated input data
        $input = $request->safe()->only([
            'title',
            'name',
            'gender',
            'phone_number',
            'avatar',
            'email',
        ]);

        if (isset($input['avatar'])) {
            // Delete the old customer's avatar
            if (! empty($customer->avatar)) {
                Storage::delete($customer->avatar);
            }

            // Store the newly customer's avatar
            $input['avatar'] = $input['avatar']->storePublicly('public/avatars');
        }

        // Update the customer's data
        $customer->update($input);

        // Refresh the customer model
        $customer->refresh();

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if (! empty($customer->avatar)) {
            // Delete the customer's avatar
            Storage::delete($customer->avatar);
        }

        // Delete the customer's data
        $customer->delete();

        return new CustomerResource($customer);
    }
}
