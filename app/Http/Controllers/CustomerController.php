<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomerDetailResource;
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
        Log::warning('Showing a list of all customers.');

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

            //
            Log::info('Stored a new customer\'s avatar.', [
                'avatar' => $input['avatar']
            ]);
        }

        // Insert the newly customer record
        $customer = Customer::create($input);

        //
        Log::info('Inserted a new customer.', [
            'customer' => $customer->id
        ]);

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
        Log::warning('Showing the customer detail.', [
            'customer' => $customer->id
        ]);

        return new CustomerDetailResource($customer);
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

            //
            Log::info('Replaced a new customer\'s avatar.', [
                'customer' => $customer->id,
                'avatar' => $input['avatar'],
            ]);
        }

        // Update the customer record
        $customer->update($input);

        // Refresh the customer model
        $customer->refresh();

        //
        Log::info('Updated a customer.', [
            'customer' => $customer->id
        ]);

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
        Log::warning('Trying to delete a customer.', [
            'customer' => $customer->id
        ]);

        if (! empty($customer->avatar)) {
            // Delete the customer's avatar
            Storage::delete($customer->avatar);

            //
            Log::info('Deleted a customer\'s avatar.', [
                'customer' => $customer->id
            ]);
        }

        // Delete the customer record
        $customer->delete();

        //
        Log::info('Deleted a customer.', [
            'customer' => $customer->id
        ]);

        return new CustomerDetailResource($customer);
    }
}
