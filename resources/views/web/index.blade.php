@extends('layouts.app')
@section('content')
	<div class="hero">
		<div class="hero__slider">
			<div class="hero__slide hero__slide--1"></div>
			<div class="hero__slide hero__slide--2"></div>
			<div class="hero__slide hero__slide--3"></div>
			<div class="hero__slide hero__slide--4"></div>
		</div>
		<div class="hero__content">
			<h1 class="hero__title">地元が誇るあの味、手軽に探せる。</h1>
			<h3 class="hero__subtitle">探す、見つける、食べる。名古屋飯の旅をはじめよう。</h3>
		</div>
	</div>
	<div class="search__block py-3 py-md-4 mb-3 mb-md-5">
		<div class="container search__block">
			<div class="row">
				<div class="col-md-4">
					<h3 class="mb-2 mb-md-3">キーワードで探す</h3>
					<form action="{{ route('shops.index') }}" method="GET" class="mb-0 shadow-sm">
						<div class="d-flex input-group">
							<input type="text" placeholder="店舗名・カテゴリで検索" name="keyword"
								class="form-control form__input form__input--muted">
							<button type="submit"class="button button--base">検索</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="mb-3 mb-md-4">
			<h3 class="mb-2 mb-md-3">おすすめの店舗</h3>
			<div class="row row-cols-2 row-cols-xl-4 g-2 g-md-3">
				@foreach ($featuredShops as $featuredShop)
					<div class="col">
						<a href="{{ route('shops.show', $featuredShop->id) }}">
							<div class="card shadow-sm">
								@if ($featuredShop->file_name !== null)
									<img src="{{ asset('uploads/' . $featuredShop->file_name) }}" class="card-img-top card__img">
								@else
									<img src="{{ asset('img/dummy.png') }}" class="card-img-top card__img">
								@endif
								<div class="card-body">
									<h5 class="fs-6 fs-md-5 fs-xl-4 mb-0">{{ $featuredShop->name }}</h5>
									@if ($featuredShop->rounded_score !== null)
										<div class="d-flex align-items-center">
											<small class="star-rating me-1" data-rate="{{ $featuredShop->rounded_score }}"></small>
											<small class="me-2">{{ $featuredShop->average_score }}</small>
										</div>
									@else
										<small class="text-muted">レビューなし</small>
									@endif
								</div>
							</div>
						</a>
					</div>
				@endforeach
			</div>
		</div>
		<div class="mb-3 mb-md-4">
			<h3 class="mb-2 mb-md-3">カテゴリで探す</h3>
			<div class="row row-cols-2 row-cols-md-4 row-cols-xl-6 g-2 g-md-3 mb-2 mb-md-3">
				@foreach ($featuredCategories as $featuredCategory)
					<div class="col">
						<a href="{{ route('shops.index', ['category' => $featuredCategory->name]) }}">
							<div class="card card--featured shadow-sm">
								@if ($featuredCategory->file_name !== null)
									<img src="{{ asset('uploads/' . $featuredCategory->file_name) }}" class="card-img-top card__category">
								@else
									<img src="{{ asset('img/dummy.png') }}" class="card-img-top">
								@endif
								<div class="card__body card-body">
									<h5 class="card__title card-title">{{ $featuredCategory->name }}</h5>
								</div>
							</div>
						</a>
					</div>
				@endforeach
			</div>
			<div>
				@foreach ($normalCategories as $normalCategory)
					<a href="{{ route('shops.index', ['category' => $normalCategory->name]) }}" class="link--style">
						<div class="button--outline me-1 me-md-2 mb-1 mb-md-2 shadow-sm">
							<small>{{ $normalCategory->name }}</small>
						</div>
					</a>
				@endforeach
			</div>
		</div>
		<div class="mb-5">
			<h3 class="mb-2 mb-md-3">新着店舗</h3>
			<div class="row row-cols-2 row-cols-md-4 g-2 g-md-3">
				@foreach ($recentlyShops as $recentlyShop)
					<div class="col">
						<a href="{{ route('shops.show', $recentlyShop->id) }}">
							<div class="card shadow-sm">
								@if ($recentlyShop->file_name !== null)
									<img src="{{ asset('uploads/' . $recentlyShop->file_name) }}" class="card-img-top card__img">
								@else
									<img src="{{ asset('img/dummy.png') }}" class="card-img-top">
								@endif
								<div class="card-body">
									<h5 class="fs-6 fs-md-5 fs-xl-4 mb-0">{{ $recentlyShop->name }}</h5>
									@if ($recentlyShop->rounded_score !== null)
										<div class="d-flex align-items-center">
											<small class="star-rating me-1" data-rate="{{ $recentlyShop->rounded_score }}"></small>
											<small class="me-2">{{ $recentlyShop->average_score }}</small>
										</div>
									@else
										<small class="text-muted">レビューなし</small>
									@endif
								</div>
							</div>
						</a>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	@vite('resources/js/slider.js')
@endsection
