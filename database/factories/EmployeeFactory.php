<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'birthday' => $this->faker->dateTimeBetween('-60 years', '-20 years'),
            'country_id' => Country::whereCode('FR')->first()->id,
            'company_id' => Company::factory(),
            'first_day' => $this->faker->dateTimeBetween('-20 years', '-1 month')
        ];
    }
}
