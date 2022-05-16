<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryTest extends TestCase
{
    protected $defaultHeaders = [
        'Accept' => 'application/json'
    ];

    /**
     * @test
     * @return void
     */
    public function assert_cannot_get_country_without_authentication()
    {
        $this->get(route('api.countries.index'))
            ->assertUnauthorized();
    }

    /**
     * @test
     * @return void
     */
    public function assert_can_get_country_with_authentication(): void
    {
        $this->initUser();

        $this->get(route('api.countries.index'))
            ->assertOk();
    }

    /**
     * @test
     * @return void
     */
    public function assert_countries_structure(): void
    {
        $this->initUser();

        $this->get(route('api.countries.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'name',
                        'code'
                    ]
                ]
            ]);
    }
}
