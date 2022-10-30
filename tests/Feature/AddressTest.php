<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\Customer;
use App\Models\Address;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
    }

    /** @test */
    public function can_insert_new_address()
    {
        $this->assertDatabaseMissing('addresses', [
            'customer_id' => $this->customer->id,
            'address' => 'Jl Setiabudi',
            'district' => 'Gegerkalong',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40152',
        ]);

        $this
            ->post(
                '/api/v1/address',
                [
                    'customer_uuid' => $this->customer->uuid,
                    'address' => 'Jl Setiabudi',
                    'district' => 'Gegerkalong',
                    'city' => 'Bandung',
                    'province' => 'Jawa Barat',
                    'postal_code' => '40152',
                ]
            )
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('address', 'Jl Setiabudi')
                        ->where('district', 'Gegerkalong')
                        ->where('city', 'Bandung')
                        ->where('province', 'Jawa Barat')
                        ->where('postal_code', '40152')
                        ->etc()
                )
            );

        $this->assertDatabaseHas('addresses', [
            'customer_id' => $this->customer->id,
            'address' => 'Jl Setiabudi',
            'district' => 'Gegerkalong',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40152',
        ]);
    }

    /** @test */
    public function can_update_new_address()
    {
        $address = Address::factory()->create(['customer_id' => $this->customer->id]);

        $this->assertDatabaseMissing('addresses', [
            'customer_id' => $this->customer->id,
            'address' => 'Jl Setiabudi 1',
            'district' => 'Gegerkalong 1',
            'city' => 'Bandung 1',
            'province' => 'Jawa Barat 1',
            'postal_code' => '40153',
        ]);

        $this
            ->patch(
                '/api/v1/address/'.$address->uuid,
                [
                    'address' => 'Jl Setiabudi 1',
                    'district' => 'Gegerkalong 1',
                    'city' => 'Bandung 1',
                    'province' => 'Jawa Barat 1',
                    'postal_code' => '40153',
                ]
            )
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('address', 'Jl Setiabudi 1')
                        ->where('district', 'Gegerkalong 1')
                        ->where('city', 'Bandung 1')
                        ->where('province', 'Jawa Barat 1')
                        ->where('postal_code', '40153')
                        ->etc()
                )
            );

        $this->assertDatabaseHas('addresses', [
            'customer_id' => $this->customer->id,
            'address' => 'Jl Setiabudi 1',
            'district' => 'Gegerkalong 1',
            'city' => 'Bandung 1',
            'province' => 'Jawa Barat 1',
            'postal_code' => '40153',
        ]);
    }

    /** @test */
    public function can_delete_address_by_uuid()
    {
        $address = Address::factory()->create(['customer_id' => $this->customer->id]);

        $this->assertModelExists($address);

        $this
            ->delete('/api/v1/address/'.$address->uuid)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('uuid', $address['uuid'])
                        ->where('address', $address['address'])
                        ->where('district', $address['district'])
                        ->where('city', $address['city'])
                        ->where('province', $address['province'])
                        ->where('postal_code', $address['postal_code'])
                        ->etc()
                )
            );

        $this->assertModelMissing($address);
    }

    /** @test */
    public function address_is_required()
    {
        $this
            ->post('/api/v1/address', ['address' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['address' => 'required']);
    }

    /** @test */
    public function address_is_too_long()
    {
        $this
            ->post('/api/v1/address', ['address' => Str::random(256)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['address' => 'greater than']);
    }

    /** @test */
    public function district_is_required()
    {
        $this
            ->post('/api/v1/address', ['district' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['district' => 'required']);
    }

    /** @test */
    public function district_is_too_long()
    {
        $this
            ->post('/api/v1/address', ['district' => Str::random(56)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['district' => 'greater than']);
    }

    /** @test */
    public function city_is_required()
    {
        $this
            ->post('/api/v1/address', ['city' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['city' => 'required']);
    }

    /** @test */
    public function city_is_too_long()
    {
        $this
            ->post('/api/v1/address', ['city' => Str::random(56)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['city' => 'greater than']);
    }

    /** @test */
    public function province_is_required()
    {
        $this
            ->post('/api/v1/address', ['province' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['province' => 'required']);
    }

    /** @test */
    public function province_is_too_long()
    {
        $this
            ->post('/api/v1/address', ['province' => Str::random(56)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['province' => 'greater than']);
    }

    /** @test */
    public function postal_code_is_required()
    {
        $this
            ->post('/api/v1/address', ['postal_code' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['postal_code' => 'required']);
    }

    /** @test */
    public function postal_code_is_too_long()
    {
        $this
            ->post('/api/v1/address', ['postal_code' => '0123456789101112'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['postal_code' => 'greater than']);
    }

    /** @test */
    public function postal_code_is_not_valid()
    {
        $this
            ->post('/api/v1/address', ['postal_code' => Str::random(16)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['postal_code' => 'invalid']);
    }
}
