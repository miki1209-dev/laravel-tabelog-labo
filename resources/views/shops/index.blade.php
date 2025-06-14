@extends('layouts.app')
@section('content')
	<div class="container py-4 py-md-5">
		<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
			<ol class="breadcrumb mb-2">
				<li class="breadcrumb-item"><a href="{{ route('top') }}">ホーム</a></li>
				<li class="breadcrumb-item active" aria-current="page">店舗一覧</li>
			</ol>
		</nav>

		@if ($keyword)
			<h3 class="mb-3 mb-md-4">{{ $keyword }}の検索結果は{{ number_format($shop_count) }}件です</h3>
		@elseif($category)
			<h3 class="mb-3 mb-md-4">{{ $category }}の検索結果は{{ number_format($shop_count) }}件です</h3>
		@elseif($keyword === null && $category === null)
			<h3 class="mb-3 mb-md-4">{{ number_format($shop_count) }}件の店舗が見つかりました</h3>
		@endif

		<div class="row gx-md-5">

			<div class="col-md-8 pe-md-0 order-2 order-md-1">
				@foreach ($shops as $shop)
					<div class="d-block d-md-flex mb-3 mb-md-4 align-items-center bg-white rounded shadow-sm p-3">
						<div class="col-md-4 mb-2 me-md-3">
							<a href="{{ route('shops.show', ['shop' => $shop->id] + $queryParams) }}">
								@if ($shop->file_name !== null)
									<img src="{{ asset('uploads/' . $shop->file_name) }}" class="img-fluid rounded shadow-sm">
								@else
									<img src="{{ asset('img/dummy.png') }}" class="img-fluid rounded shadow-sm">
								@endif
							</a>
						</div>
						<div class="col-md-8">
							<h5>{{ $shop->name }}</h5>
							<div class="mb-2">
								@foreach ($shop->categories as $category)
									<span class="badge__category me-1">{{ $category->name }}</span>
								@endforeach
							</div>
							<p class="mb-1">
								<i class="fas fa-phone me-2 text-muted"></i>{{ $shop->formatted_phone_number }}
							</p>
							<p class="mb-1">
								<i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $shop->address }}
							</p>
							<p class="mb-0">
								<i
									class="fas fa-clock me-2 text-muted"></i>{{ $shop->formatted_opening_time }}〜{{ $shop->formatted_closing_time }}
							</p>
						</div>
					</div>
				@endforeach

				<div class="mb-0 mb-md-4">
					{{ $shops->links() }}
				</div>
			</div>

			<div class="col-md-4 pe-md-0 mt-0 order-1 order-md-2 mb-3 mb-md-0">
				<div class="p-3 p-md-4 bg-white rounded shadow-sm">
					<h5 class="fw-bold mb-3 border-bottom pb-2">キーワードで探す</h5>
					<form action="{{ route('shops.index') }}" method="GET" class="mb-3">
						<input type="text" placeholder="店舗名・カテゴリで検索" name="keyword"
							class="form-control form__input form__input--muted mb-3" value="{{ request('keyword') }}">
						<button type="submit" class="button button--base w-100">検索</button>
					</form>

					<h5 class="fw-bold mb-3 border-bottom pb-2">カテゴリで探す</h5>
					<form action="{{ route('shops.index') }}" method="GET">
						<div class="mb-3">
							<select class="form-select form__select form__select--muted" name="category">
								<option value="">カテゴリを選択</option>
								@foreach ($categoriesName as $categoryName)
									<option value="{{ $categoryName }}" {{ request('category') === $categoryName ? 'selected' : '' }}>
										{{ $categoryName }}
									</option>
								@endforeach
							</select>
						</div>
						<button type="submit" class="button button--base w-100">カテゴリ検索</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
