@extends('layouts.app')
@section('content')
	<div class="container py-4 py-md-5">
		<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
			<ol class="breadcrumb mb-1 mb-md-2">
				<li class="breadcrumb-item"><a href="{{ route('top') }}">ホーム</a></li>
				<li class="breadcrumb-item"><a href="{{ route('shops.index') }}">店舗一覧</a></li>
				<li class="breadcrumb-item"><a href="{{ route('shops.index', $shop->id) }}">店舗詳細</a></li>
				<li class="breadcrumb-item active" aria-current="page">予約内容確認</li>
			</ol>
		</nav>

		<div class="mb-2 mb-md-4">
			<h3>予約内容確認</h3>
		</div>

		<hr class="my-3 my-md-4">

		<div class="reservation">
			<ul class="list-group reservation__list">
				<li class="list-group-item reservation__item"><strong>店舗名：</strong>{{ $shop->name }}</li>
				<li class="list-group-item reservation__item"><strong>来店日：</strong>{{ $reservation->formatted_visit_date }}</li>
				<li class="list-group-item reservation__item"><strong>来店時間：</strong>{{ $reservation->formatted_visit_time }}</li>
				<li class="list-group-item reservation__item"><strong>人数：</strong>{{ $reservation->number_of_people }}人</li>
				<li class="list-group-item reservation__item"><strong>電話番号：</strong>{{ $shop->formatted_phone_number }}</li>
				<li class="list-group-item reservation__item"><strong>住所：</strong>{{ $shop->address }}</li>
			</ul>
		</div>
		<div class="d-flex justify-content-center gap-3">
			<form action="{{ route('reservations.store') }}" method="POST">
				@csrf
				<input type="hidden" name="shop_id" value="{{ $reservation->shop_id }}">
				<input type="hidden" name="visit_date" value="{{ $reservation->visit_date }}">
				<input type="hidden" name="visit_time" value="{{ $reservation->visit_time }}">
				<input type="hidden" name="number_of_people" value="{{ $reservation->number_of_people }}">
				<button type="submit" class="button button--base">予約を確定</button>
			</form>

			<a href="{{ route('shops.show', $shop->id) }}" class="button button--base button--danger">
				キャンセル
			</a>
		</div>
	</div>
@endsection
