<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'name' => fake()->name(),
			'email' => fake()->unique()->safeEmail(),
			'email_verified_at' => now(),
			'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
			'remember_token' => Str::random(10),
		];
	}

	public function withPremiumSubscription(): self
	{
		return $this->afterCreating(function (User $user) {
			$user->createAsStripeCustomer();

			$user->subscriptions()->create([
				'name' => 'premium',
				'stripe_id' => 'sub_fake_' . Str::random(8),
				'stripe_status' => 'active',
				'stripe_price' => config('cashier.plan_id'),
				'quantity' => 1,
			]);
		});
	}

	/**
	 * Indicate that the model's email address should be unverified.
	 *
	 * @return static
	 */
	public function unverified()
	{
		return $this->state(fn(array $attributes) => [
			'email_verified_at' => null,
		]);
	}
}
