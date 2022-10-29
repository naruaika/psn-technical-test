<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Customer;

class AddressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAddressRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddressRequest $request)
    {
        // Get the validated input data
        $input = $request->safe()->only([
            'customer_uuid',
            'address',
            'district',
            'city',
            'province',
            'postal_code',
        ]);

        // Get the related customer model
        $customer = Customer::query()
            ->select('id', 'uuid')
            ->where('uuid', $input['customer_uuid'])
            ->firstOrFail();

        // Get the related customer's ID
        $input['customer_id'] = $customer->id;
        unset($input['customer_uuid']);

        // Insert the newly address record
        $address = Address::create($input);

        return new AddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAddressRequest  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        // Get the validated input data
        $input = $request->safe()->only([
            'address',
            'district',
            'city',
            'province',
            'postal_code',
        ]);

        // Update the address record
        $address->update($input);

        // Refresh the address model
        $address->refresh();

        return new AddressResource($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        // Delete the address record
        $address->delete();

        return new AddressResource($address);
    }
}
