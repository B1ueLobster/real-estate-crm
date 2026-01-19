<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Property;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title'=> fake()->sentence(4),
            //Generate description
            'description'=>fake()->paragraph(3),
            //Price between 300000 and 1000000
            'price'=>fake()->numberBetween(300000,1000000),
            //Address
            'city'=>fake()->city(),
            'region' => fake()->randomElement(['Івано-Франківська обл.', 'Львівська обл.', 'Тернопільська обл.']),
            'district'=>fake()->streetAddress(),
            'street_address' => fake()->streetName(),
            'street_number' => fake()->buildingNumber(),
            'zip_code'=>fake()->postcode(),
            'area'=>fake()->numberBetween(30,200),
            //Random select from a list
            'status'=>fake()->randomElement(['active','sold','rented']),
            'type'=>fake()->randomElement(['apartment','house','land']),
        ];
    }
}
