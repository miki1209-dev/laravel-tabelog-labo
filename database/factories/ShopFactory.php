<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'name' => $this->faker->company(),
			'description' => $this->faker->text(100),
			'address' => $this->faker->address(),
			'phone_number' => $this->faker->phoneNumber(),
			'opening_time' => $this->faker->time('H:i', '09:00'),
			'closing_time' => $this->faker->time('H:i', '23:00'),
		];
	}
}
