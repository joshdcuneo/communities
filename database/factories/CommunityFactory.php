<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Community>
 *
 * @method static self hasInvitiations(int $count)
 * @method static self hasUsers(int $count)
 */
class CommunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'owner_id' => User::factory(),
        ];
    }

    public function forOwner(User|UserFactory $user): static
    {
        return $this->for($user, 'owner')->hasAttached($user);
    }
}
