<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'lost', 'customer']),
            'source' => fake()->randomElement(['website', 'referral', 'social', 'other']),
            'notes' => fake()->sentence(),
            // Linked a Lead to a random User
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
