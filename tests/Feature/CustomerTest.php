<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\Customer;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('lokal');

        $this->customers = Customer::factory()->count(3)->create();
        $this->avatar = UploadedFile::fake()->image('avatar.jpg');
    }

    /** @test */
    public function can_get_all_customers()
    {
        $customer = $this->customers[0];

        $this
            ->get('/api/v1/customer')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 3, fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('uuid', $customer['uuid'])
                        ->where('title', $customer['title'])
                        ->where('name', $customer['name'])
                        ->where('gender', $customer['gender'])
                        ->where('phone_number', $customer['phone_number'])
                        ->where('email', $customer['email'])
                        ->has('avatar')
                        ->etc()
                )
            );
    }

    /** @test */
    public function can_insert_new_customer()
    {
        $this->assertDatabaseMissing('customers', [
            'title' => 'Mr.',
            'name' => 'Naru Aika',
            'gender' => 'M',
            'phone_number' => '081234567890',
            'email' => 'naru.aika@localhost.com',
        ]);

        $this
            ->post(
                '/api/v1/customer',
                [
                    'title' => 'Mr.',
                    'name' => 'Naru Aika',
                    'gender' => 'M',
                    'phone_number' => '081234567890',
                    'email' => 'naru.aika@localhost.com',
                    'avatar' => $this->avatar,
                ]
            )
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('title', 'Mr.')
                        ->where('name', 'Naru Aika')
                        ->where('gender', 'M')
                        ->where('phone_number', '081234567890')
                        ->where('email', 'naru.aika@localhost.com')
                        ->has('avatar')
                        ->etc()
                )
            );

        $this->assertDatabaseHas('customers', [
            'title' => 'Mr.',
            'name' => 'Naru Aika',
            'gender' => 'M',
            'phone_number' => '081234567890',
            'email' => 'naru.aika@localhost.com',
        ]);

        Storage::disk('local')->assertExists('public/avatars/'.$this->avatar->hashName());
    }

    /** @test */
    public function can_get_customer_by_uuid()
    {
        $customer = Customer::factory()->create();

        $this
            ->get('/api/v1/customer/'.$customer->uuid)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('uuid', $customer['uuid'])
                        ->where('title', $customer['title'])
                        ->where('name', $customer['name'])
                        ->where('gender', $customer['gender'])
                        ->where('phone_number', $customer['phone_number'])
                        ->where('email', $customer['email'])
                        ->has('avatar')
                        ->has('addresses')
                        ->whereType('addresses', ['array'])
                        ->etc()
                )
            );
    }

    /** @test */
    public function can_update_customer_by_uuid()
    {
        $customer = Customer::factory()->create();

        $this
            ->patch(
                '/api/v1/customer/'.$customer->uuid,
                [
                    'title' => 'Mr.',
                    'name' => 'Naru Aika 1',
                    'gender' => 'M',
                    'phone_number' => '081234567891',
                    'email' => 'naru.aika+1@localhost.com',
                    'avatar' => $this->avatar,
                ]
            )
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('title', 'Mr.')
                        ->where('name', 'Naru Aika 1')
                        ->where('gender', 'M')
                        ->where('phone_number', '081234567891')
                        ->where('email', 'naru.aika+1@localhost.com')
                        ->has('avatar')
                        ->etc()
                )
            );

        $this->assertDatabaseHas('customers', [
            'title' => 'Mr.',
            'name' => 'Naru Aika 1',
            'gender' => 'M',
            'phone_number' => '081234567891',
            'email' => 'naru.aika+1@localhost.com',
        ]);

        Storage::disk('local')->assertExists('public/avatars/'.$this->avatar->hashName());
    }

    /** @test */
    public function can_delete_customer_by_uuid()
    {
        $customer = Customer::factory()->create();

        $this->assertModelExists($customer);

        $this
            ->delete('/api/v1/customer/'.$customer->uuid)
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn ($json) =>
                    $json
                        ->missing('id')
                        ->where('uuid', $customer['uuid'])
                        ->where('title', $customer['title'])
                        ->where('name', $customer['name'])
                        ->where('gender', $customer['gender'])
                        ->where('phone_number', $customer['phone_number'])
                        ->where('email', $customer['email'])
                        ->has('avatar')
                        ->has('addresses')
                        ->whereType('addresses', ['array'])
                        ->etc()
                )
            );

        $this->assertModelMissing($customer);

        Storage::disk('local')->assertMissing('public/avatars/'.$this->avatar->hashName());
    }

    /** @test */
    public function title_is_too_short()
    {
        $this
            ->post('/api/v1/customer', ['title' => 'M'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title' => 'at least']);
    }

    /** @test */
    public function title_is_too_long()
    {
        $this
            ->post('/api/v1/customer', ['title' => Str::random(31)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title' => 'greater than']);
    }

    /** @test */
    public function name_is_required()
    {
        $this
            ->post('/api/v1/customer', ['name' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name' => 'required']);
    }

    /** @test */
    public function name_is_too_long()
    {
        $this
            ->post('/api/v1/customer', ['name' => Str::random(256)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name' => 'greater than']);
    }

    /** @test */
    public function gender_is_not_valid()
    {
        $this
            ->post('/api/v1/customer', ['gender' => 'X'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['gender' => 'invalid']);
    }

    /** @test */
    public function phone_number_is_required()
    {
        $this
            ->post('/api/v1/customer', ['phone_number' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['phone_number' => 'required']);
    }

    /** @test */
    public function phone_number_is_too_long()
    {
        $this
            ->post('/api/v1/customer', ['phone_number' => '0123456789101112'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['phone_number' => 'greater than']);
    }

    /** @test */
    public function phone_number_is_not_valid()
    {
        $this
            ->post('/api/v1/customer', ['phone_number' => Str::random(15)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['phone_number' => 'invalid']);
    }

    /** @test */
    public function avatar_is_not_accepted()
    {
        $avatar = UploadedFile::fake()->image('avatar.gif');

        $this
            ->post('/api/v1/customer', ['avatar' => $avatar])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['avatar' => 'of type']);
    }

    /** @test */
    public function avatar_is_too_large()
    {
        $avatar = UploadedFile::fake()->image('avatar.jpg')->size(1024);

        $this
            ->post('/api/v1/customer', ['avatar' => $avatar])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['avatar' => 'greater than']);
    }

    /** @test */
    public function email_is_required()
    {
        $this
            ->post('/api/v1/customer', ['email' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email' => 'required']);
    }

    /** @test */
    public function email_is_too_long()
    {
        $this
            ->post('/api/v1/customer', ['email' => Str::random(256)])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email' => 'greater than']);
    }

    /** @test */
    public function email_is_not_unique()
    {
        $this
            ->post('/api/v1/customer', ['email' => $this->customers[0]['email']])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email' => 'taken']);
    }
}
