<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'display_name' => $this->faker->name(),
            'daily_cigarettes' => $this->faker->numberBetween(1, 60),
            'cigarette_pack_cost' => $this->faker->numberBetween(400, 3000),
            'quit_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
