<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Address;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = Customer::factory()
            ->count(10)
            ->create();

        foreach ($customers as $customer) {
            Address::factory()
                ->count(3)
                ->create(['customer_id' => $customer->id]);
        }
    }
}
