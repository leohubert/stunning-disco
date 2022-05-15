<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $seller = $this->faker->randomElement([Company::class, Provider::class]);
        $buyer = $this->faker->randomElement([Company::class, Client::class]);

        return [
            'seller_id' => $seller::factory(),
            'seller_type' => $seller,
            'buyer_id' => $buyer::factory(),
            'buyer_type' => $buyer,

            'product_id' => Product::factory(),
            'amount' => $this->faker->randomNumber(),

            'responsible' => Employee::factory()
        ];
    }
}
