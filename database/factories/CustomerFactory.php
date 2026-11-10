<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the fake data for one customer.
     */
    public function definition(): array
    {
        // Pick the gender first so the generated name matches it
        $gender = fake()->randomElement(['Male', 'Female']);

        return [
            'name'        => fake()->name(strtolower($gender)),
            'email'       => fake()->unique()->safeEmail(),
            'address'     => fake()->address(),
            'phoneNumber' => fake()->phoneNumber(),
            'gender'      => $gender,
            // Range includes dates after 2000 so the "Born After 2000" page has data
            'birthday'    => fake()->dateTimeBetween('1960-01-01', '2008-12-31')->format('Y-m-d'),
        ];
    }
}
