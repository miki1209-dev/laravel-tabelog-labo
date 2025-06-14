<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\Reservation;

class ReservationFeatureTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	use RefreshDatabase;

	//有料会員で予約を登録した場合の動作確認
	public function test_premium_user_can_create_reservation()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '18:00',
			'number_of_people' => 2,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertRedirect();
		$this->assertDatabaseHas('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'visit_date' => $reservationData['visit_date'],
			'visit_time' => '18:00',
			'number_of_people' => $reservationData['number_of_people'],
			'status' => 'confirmed',
		]);
	}

	//有料会員で同じ日付で別時間で予約を登録した場合の動作確認
	public function test_user_can_create_multiple_reservations_for_different_times()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$date = now()->addDays(1)->format('Y-m-d');

		$firstReservation = [
			'shop_id' => $shop->id,
			'visit_date' => $date,
			'visit_time' => '18:00',
			'number_of_people' => 2,
		];

		$this->actingAs($user)->post(route('reservations.store'), $firstReservation);

		$secondReservation = [
			'shop_id' => $shop->id,
			'visit_date' => $date,
			'visit_time' => '19:00',
			'number_of_people' => 2,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $secondReservation);

		$response->assertRedirect();

		$this->assertDatabaseHas('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'visit_date' => $date,
			'visit_time' => '18:00',
		]);

		$this->assertDatabaseHas('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'visit_date' => $date,
			'visit_time' => '19:00',
		]);
	}

	//有料会員で同じ日付で別のユーザーが予約を登録した場合の動作確認
	public function test_different_users_can_reserve_same_shop_at_same_time()
	{
		$userA = User::factory()->withPremiumSubscription()->create();
		$userB = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '18:00',
			'number_of_people' => 2,
		];

		$this->actingAs($userA)->post(route('reservations.store'), $reservationData);

		$response = $this->actingAs($userB)->post(route('reservations.store'), $reservationData);

		$response->assertRedirect();

		$this->assertDatabaseHas('reservations', [
			'user_id' => $userA->id,
			'shop_id' => $shop->id,
			'visit_date' => $reservationData['visit_date'],
			'visit_time' => $reservationData['visit_time'],
		]);

		$this->assertDatabaseHas('reservations', [
			'user_id' => $userB->id,
			'shop_id' => $shop->id,
			'visit_date' => $reservationData['visit_date'],
			'visit_time' => $reservationData['visit_time'],
		]);
	}

	//無料会員で予約を登録した場合の動作確認
	public function test_free_user_cannot_create_reservation()
	{
		$user = User::factory()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '18:00',
			'number_of_people' => 2,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertRedirect(route('mypage.subscription'));
		$this->assertDatabaseMissing('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'visit_date' => $reservationData['visit_date'],
		]);
	}

	// 予約日をのみ未入力で予約を実行した場合の動作確認
	public function test_visit_date_is_required()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => '',
			'visit_time' => '18:00',
			'number_of_people' => 2,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertSessionHasErrors(['visit_date'], null, 'reservation');
		$this->assertDatabaseMissing('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	// 予約時間のみを未入力で予約を実行した場合の動作確認
	public function test_visit_time_is_required()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '',
			'number_of_people' => 2,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertSessionHasErrors(['visit_time'], null, 'reservation');
		$this->assertDatabaseMissing('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	// 予約人数のみを未入力で予約を実行した場合の動作確認
	public function test_number_of_people_is_required()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '18:00',
			'number_of_people' => null,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertSessionHasErrors(['number_of_people'], null, 'reservation');
		$this->assertDatabaseMissing('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	// 予約人数を負の値で予約を実行した場合の動作確認
	public function test_number_of_people_must_be_at_least_one()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '18:00',
			'number_of_people' => 0,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertSessionHasErrors(['number_of_people'], null, 'reservation');
		$this->assertDatabaseMissing('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	// 予約人数を上限より超えた人数で予約を実行した場合の動作確認
	public function test_number_of_people_must_not_exceed_maximum()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => now()->addDays(1)->format('Y-m-d'),
			'visit_time' => '18:00',
			'number_of_people' => 16,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertSessionHasErrors(['number_of_people'], null, 'reservation');
		$this->assertDatabaseMissing('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	//同じユーザーが同じに日付で予約を実行した場合の動作確認の動作確認
	public function test_user_cannot_create_duplicate_reservation_same_datetime()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		Reservation::create([
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'visit_date' => '2025-06-10',
			'visit_time' => '18:00:00',
			'number_of_people' => 2,
			'status' => 'confirmed',
		]);

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => '2025-06-10',
			'visit_time' => '18:00:00',
			'number_of_people' => 2,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertSessionHasErrors('visit_date', null, 'reservation');
		$this->assertCount(1, Reservation::where('user_id', $user->id)->get());
	}

	//同じユーザーが同じに日付で予約を実行した場合(キャンセル済みの予定に対して)の動作確認
	public function test_user_can_create_reservation_if_previous_is_canceled()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$visit_date = now()->addDays(1)->format('Y-m-d');
		$visit_time = '18:00';

		Reservation::factory()->create([
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'visit_date' => $visit_date,
			'visit_time' => $visit_time,
			'number_of_people' => 2,
			'status' => 'canceled',
		]);

		$reservationData = [
			'shop_id' => $shop->id,
			'visit_date' => $visit_date,
			'visit_time' => $visit_time,
			'number_of_people' => 2,
		];

		$response = $this->actingAs($user)->post(route('reservations.store'), $reservationData);

		$response->assertRedirect(route('reservations.complete'));

		$this->assertDatabaseHas('reservations', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'visit_date' => $visit_date,
			'visit_time' => $visit_time,
			'status' => 'canceled',
		]);
	}
}
