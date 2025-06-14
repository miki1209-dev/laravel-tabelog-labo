<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
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
			'shop_id' => Shop::factory(),
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '18:00',
			'number_of_people' => 2,
			'status' => 'confirmed',
		];
	}
}
