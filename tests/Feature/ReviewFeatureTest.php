<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use App\Models\Review;

class ReviewFeatureTest extends TestCase
{
	/**
	 * A basic feature test example.
	 *
	 * @return void
	 */
	use RefreshDatabase;

	//有料会員でレビューを投稿した場合の動作確認
	public function test_premium_user_can_add_shop_to_reviews()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$reviewData = [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => 'うまい',
			'content' => 'カツカレーうますぎ',
			'score' => 3
		];

		$response = $this->actingAs($user)->post(route('reviews.store', $shop->id), $reviewData);
		$response->assertRedirect();
		$this->assertDatabaseHas('reviews', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => $reviewData['title'],
			'content' => $reviewData['content'],
			'score' => $reviewData['score']
		]);
	}

	//有料会員でレビューを編集し保存した場合の動作確認
	public function test_premium_user_can_update_shop_to_reviews()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$review = Review::factory()->create([
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);

		$reviewData = [
			'title' => 'うまいうまい',
			'content' => 'カツカレーうますぎないくらいがいい',
			'score' => 4
		];

		$response = $this->actingAs($user)->put(route('reviews.update', $review->id), $reviewData);
		$response->assertRedirect();
		$this->assertDatabaseHas('reviews', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => $reviewData['title'],
			'content' => $reviewData['content'],
			'score' => $reviewData['score']
		]);
	}

	//有料会員でレビューを削除した場合の動作確認
	public function test_premium_user_can_destroy_shop_to_reviews()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$review = Review::factory()->create([
			'user_id' => $user->id,
			'shop_id' => $shop->id,
		]);

		$response = $this->actingAs($user)->delete(route('reviews.destroy', $review->id));
		$response->assertRedirect();
		$this->assertDatabaseMissing('reviews', [
			'id' => $review->id,
		]);
	}

	//無料会員でレビューを投稿した場合の動作確認
	public function test_free_user_cannot_add_shop_to_reviews()
	{
		$user = User::factory()->create();
		$shop = Shop::factory()->create();

		$reviewData = [
			'shop_id' => $shop->id,
			'title' => 'うまい',
			'content' => 'カツカレーうますぎ',
			'score' => 3
		];

		$response = $this->actingAs($user)->post(route('reviews.store', $shop->id), $reviewData);
		$response->assertRedirect(route('mypage.subscription'));
		$this->assertDatabaseMissing('reviews', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => $reviewData['title'],
			'content' => $reviewData['content'],
			'score' => $reviewData['score']
		]);
	}

	//無料会員でレビューを編集し保存した場合の動作確認
	public function test_free_user_cannot_update_shop_to_reviews()
	{
		$user = User::factory()->create();
		$shop = Shop::factory()->create();

		$review = Review::factory()->create([
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => '初期タイトル',
			'content' => '初期コンテンツ',
			'score' => 2,
		]);

		$reviewData = [
			'title' => 'うまいうまい',
			'content' => 'カツカレーうますぎないくらいがいい',
			'score' => 4
		];

		$response = $this->actingAs($user)->put(route('reviews.update', $review->id), $reviewData);
		$response->assertRedirect(route('mypage.subscription'));
		$this->assertDatabaseHas('reviews', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => '初期タイトル',
			'content' => '初期コンテンツ',
			'score' => 2
		]);
	}

	//無料会員でレビューを削除した場合の動作確認
	public function test_free_user_cannot_destroy_shop_to_reviews()
	{
		$user = User::factory()->create();
		$shop = Shop::factory()->create();

		$review = Review::factory()->create([
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => '初期タイトル',
			'content' => '初期コンテンツ',
			'score' => 2,
		]);

		$response = $this->actingAs($user)->delete(route('reviews.destroy', $review->id));
		$response->assertRedirect(route('mypage.subscription'));
		$this->assertDatabaseHas('reviews', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => '初期タイトル',
			'content' => '初期コンテンツ',
			'score' => 2
		]);
	}

	//不正なスコア入力した場合の動作確認
	public function test_review_post_fails_with_invalid_score()
	{
		$user = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$invalidData = [
			'shop_id' => $shop->id,
			'title' => '評価ミス',
			'content' => '点数が変',
			'score' => 6
		];

		$response = $this->actingAs($user)->post(route('reviews.store', $shop->id), $invalidData);

		$response->assertSessionHasErrors('score');
		$this->assertDatabaseMissing('reviews', [
			'user_id' => $user->id,
			'shop_id' => $shop->id,
			'title' => $invalidData['title'],
		]);
	}

	//別ユーザーの投稿を編集を実行した場合の動作確認
	public function test_user_cannot_edit_others_review()
	{
		$userA = User::factory()->withPremiumSubscription()->create();
		$userB = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$review = Review::factory()->create([
			'user_id' => $userB->id,
			'shop_id' => $shop->id,
		]);

		$updateData = [
			'title' => '不正アクセス',
			'content' => '他人のレビュー編集',
			'score' => 1,
		];

		$response = $this->actingAs($userA)->put(route('reviews.update', $review->id), $updateData);

		$response->assertStatus(403);
		$this->assertDatabaseMissing('reviews', [
			'user_id' => $userB->id,
			'title' => $updateData['title'],
		]);
	}

	//別ユーザーの投稿を削除を実行した場合の動作確認
	public function test_user_cannot_delete_others_review()
	{
		$userA = User::factory()->withPremiumSubscription()->create();
		$userB = User::factory()->withPremiumSubscription()->create();
		$shop = Shop::factory()->create();

		$review = Review::factory()->create([
			'user_id' => $userB->id,
			'shop_id' => $shop->id,
		]);

		$response = $this->actingAs($userA)->delete(route('reviews.destroy', $review->id));
		$response->assertStatus(403);

		$this->assertDatabaseHas('reviews', [
			'id' => $review->id,
		]);
	}
}
