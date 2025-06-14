@extends('layouts.app')
@section('content')
	<div class="container py-4 py-md-5">
		<div class="row justify-content-center">
			<div class="col-md-8">
				@if (session('success'))
					<div class="flash-message flash-message--success">
						<i class="fas fa-check-circle"></i>
						<span>{{ session('success') }}</span>
					</div>
				@endif
				@if (session('error'))
					<div class="alert alert-danger">{{ session('error') }}</div>
				@endif
				<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
					<ol class="breadcrumb mb-1 mb-md-2">
						<li class="breadcrumb-item"><a href="{{ route('mypage') }}">マイページ</a></li>
						<li class="breadcrumb-item active" aria-current="page">お気に入り一覧</li>
					</ol>
				</nav>

				<div class="mb-2 mb-md-4">
					<h3>お気に入り一覧</h3>
				</div>

				<hr class="my-3 my-md-4">

				@if ($favoritesShops->isEmpty())
					<div class="row">
						<p class="mb-0">お気に入りはまだ追加されていません。</p>
					</div>
				@else
					@foreach ($favoritesShops as $favoritesShop)
						<div class="row align-items-center mb-md-2">
							<div class="col-md-4 mb-2 mb-md-0">
								<a href="{{ route('shops.show', $favoritesShop->id) }}">
									@if ($favoritesShop->file_name !== null)
										<img src="{{ asset('uploads/' . $favoritesShop->file_name) }}" class="img-thumbnail">
									@else
										<img src="{{ asset('img/dummy.png') }}" class="img-thumbnail">
									@endif
								</a>
							</div>
							<div class="col-md-6 mb-2 mb-md-0">
								<h5 class="w-100">
									<a href="{{ route('shops.show', $favoritesShop->id) }}" class="link-dark ">店舗名：{{ $favoritesShop->name }}</a>
								</h5>
								<div>
									<small>住所：{{ $favoritesShop->address }}</small><br>
									<small>電話番号：{{ $favoritesShop->formatted_phone_number }}</small><br>
									<small>営業時間：{{ $favoritesShop->formatted_opening_time }}〜{{ $favoritesShop->formatted_closing_time }}</small>
								</div>
							</div>
							<div class="col-md-2">
								<button type="button" class="button button--base button--danger button--sp favorite-delete-button"
									data-shop-id="{{ $favoritesShop->id }}">
									削除
								</button>
							</div>
							<hr class="my-3 my-md-4">
						</div>
					@endforeach
				@endif

				<div class="mb-4">
					{{ $favoritesShops->links() }}
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	@vite('resources/js/favorite-delete.js')
@endsection
