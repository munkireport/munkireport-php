<?php

namespace Database\Factories;

use App\Models\Team;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'role' => 'user',
            'remember_token' => Str::random(10),
            'source' => null,
            'display_name' => fake()->name(),
            'profile_photo_path' => null,
            'current_team_id' => null,
            'locale' => 'en_US',
        ];
    }

    /**
     * Indicate that the user should have the global admin role
     */
    public function admin(): static
    {
        return $this->state(function (array $attributes) {
           return [
               'role' => 'admin',
           ];
        });
    }

    /**
     * Indicate that the user should have the global manager role
     */
    public function manager(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'manager',
            ];
        });
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
