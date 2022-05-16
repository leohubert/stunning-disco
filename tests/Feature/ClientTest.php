<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultHeaders = [
        'Accept' => 'application/json'
    ];

    /**
     * @test
     * @return void
     */
    public function assert_cannot_list_client_when_unauthenticated(): void
    {
        Client::factory(10)->create();

        $this->get(route('api.clients.index'))
            ->assertUnauthorized();
    }

    /**
     * @test
     * @return void
     */
    public function assert_can_list_client(): void
    {
        $this->initUser();

        Client::factory(10)->create();

        $this->get(route('api.clients.index'))
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'name',
                        'address',
                        'country' => [
                            'code',
                            'name'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     * @return void
     */
    public function assert_cannot_create_client_when_unauthenticated(): void
    {
        $this->post(route('api.clients.store'), [])
            ->assertUnauthorized();
    }

    /**
     * @test
     * @return void
     */
    public function assert_cannot_create_with_non_admin_user()
    {
        $this->initUser(false);

        $country = Country::whereCode('FR')->first();

        $this->post(route('api.clients.store'), [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country' => 'FR'
        ])->assertForbidden();

        $this->assertDatabaseMissing('clients', [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country_id' => $country->id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function assert_can_create_client(): void
    {
        $this->initUser();

        $country = Country::whereCode('FR')->first();

        $this->post(route('api.clients.store'), [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country' => 'FR'
        ])->assertCreated();

        $this->assertDatabaseHas('clients', [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country_id' => $country->id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function assert_cannot_update_client_when_unauthenticated(): void
    {
        $client = Client::factory()->create();

        $this->put(route('api.clients.update', $client->id), [])
            ->assertUnauthorized();
    }

    /**
     * @test
     * @return void
     */
    public function assert_cannot_update_client_without_admin_user()
    {
        $this->initUser(false);

        $client = Client::factory()->create();

        $this->put(route('api.clients.update', $client->id), [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country' => 'FR'
        ])->assertForbidden();

        $this->assertDatabaseMissing('clients', [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country_id' => $client->country_id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function assert_can_update_client(): void
    {
        $this->initUser();

        $client = Client::factory()->create();

        $this->put(route('api.clients.update', $client->id), [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country' => 'FR'
        ])->assertNoContent();

        $this->assertDatabaseHas('clients', [
            'name' => 'Client 1',
            'address' => 'Address 1',
            'country_id' => $client->country_id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function assert_can_delete_client(): void
    {
        $this->initUser();

        $client = Client::factory()->create();

        $this->delete(route('api.clients.destroy', $client->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function assert_cannot_delete_client_when_unauthenticated(): void
    {
        $client = Client::factory()->create();

        $this->delete(route('api.clients.destroy', $client->id))
            ->assertUnauthorized();

        $this->assertDatabaseHas('clients', [
            'id' => $client->id
        ]);
    }
}
