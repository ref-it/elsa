<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * Users are only ever created through the OIDC callback, so there is no
     * password: the token columns stand in for what the provider returns.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstname = fake()->firstName();
        $lastname = fake()->lastName();

        return [
            'oidc_sub' => fake()->uuid(),
            'username' => fake()->unique()->userName(),
            'name' => $firstname.' '.$lastname,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => fake()->unique()->safeEmail(),
            'groups' => json_encode([]),
            'avatar' => null,
            'oidc_token' => Str::random(40),
            'oidc_refresh_token' => Str::random(40),
            'oidc_id_token' => Str::random(40),
        ];
    }

    /**
     * Indicate which OIDC groups the user belongs to.
     *
     * @param  list<string>  $groups
     */
    public function inGroups(array $groups): static
    {
        return $this->state(fn (array $attributes) => [
            'groups' => json_encode($groups),
        ]);
    }
}
