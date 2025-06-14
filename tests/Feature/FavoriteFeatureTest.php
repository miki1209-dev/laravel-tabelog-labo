<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavoriteFeatureTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	use RefreshDatabase;

	//有料会員登録でお気に入りした場合の動作確認
	public function test_premium_user_can_add_shop_to_favorites()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$response = $this->actingAs($user)->post(route('favorite.store', $shop->id));
		$response->assertRedirect();

		$this->assertDatabaseHas('favorites', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	//有料会員登録でお気に入りを削除した場合の動作確認
	public function test_premium_user_can_destroy_shop_to_favorites()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$user->favorite_shops()->attach($shop->id);

		$response = $this->actingAs($user)->delete(route('favorite.destroy', $shop->id));
		$response->assertRedirect();

		$this->assertDatabaseMissing('favorites', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	//無料会員登録でお気に入りした場合の動作確認
	public function test_free_user_can_add_shop_to_favorites()
	{
		$user = User::factory()->create();
		$shop = Shop::factory()->create();

		$response = $this->actingAs($user)->post(route('favorite.store', $shop->id));

		$response->assertRedirect(route('mypage.subscription'));
		$this->assertDatabaseMissing('favorites', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	//無料会員登録でお気に入りを削除した場合の動作確認
	public function test_free_user_can_destroy_shop_to_favorites()
	{
		$user = User::factory()->create();
		$shop = Shop::factory()->create();

		$user->favorite_shops()->attach($shop->id);

		$response = $this->actingAs($user)->delete(route('favorite.destroy', $shop->id));
		$response->assertRedirect(route('mypage.subscription'));

		$this->assertDatabaseHas('favorites', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);
	}

	// 存在しない店舗IDで登録しようとした場合の動作確認
	public function test_premium_user_cannot_add_favorite_to_nonexistent_shop()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$nonExistentShopId = 999999;

		$response = $this->actingAs($user)->post(route('favorite.store', $nonExistentShopId));
		$response->assertNotFound();
	}


	// 存在しない店舗IDで削除しようとした場合
	public function test_premium_user_cannot_remove_favorite_from_nonexistent_shop()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$nonExistentShopId = 999999;

		$response = $this->actingAs($user)->delete(route('favorite.destroy', $nonExistentShopId));
		$response->assertNotFound();
	}
}
