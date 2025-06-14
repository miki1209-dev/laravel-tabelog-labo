@extends('layouts.app')
@section('content')
	<div class="container py-4 py-md-5">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				@if (session('success'))
					<div class="flash-message flash-message--success">
						<i class="fas fa-check-circle"></i>
						<span>{{ session('success') }}</span>
					</div>
				@endif
				@if (session('error'))
					<div class="alert alert-danger">{{ session('error') }}</div>
				@endif

				@if ($errors->has('db_error') || $errors->has('general_error'))
					<div class="alert alert-light">
						{{ $errors->first('db_error') ?? $errors->first('general_error') }}
					</div>
				@endif
				<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
					<ol class="breadcrumb mb-1 mb-md-2">
						<li class="breadcrumb-item"><a href="{{ route('mypage') }}">マイページ</a></li>
						<li class="breadcrumb-item active" aria-current="page">予約一覧</li>
					</ol>
				</nav>

				<div class="mb-2 mb-md-4">
					<h3>予約一覧</h3>
				</div>

				<hr class="my-3 my-md-4">

				@if ($reservations->isEmpty())
					<div class="row">
						<p class="mb-0">ご予約はされていません。</p>
					</div>
				@else
					@foreach ($reservations as $reservation)
						<div class="row align-items-center mb-md-2">
							<div class="col-md-4 mb-2 mb-md-0">
								<a href="{{ route('shops.show', $reservation->shop_id) }}">
									@if ($reservation->shop->file_name !== null)
										<img src="{{ asset('uploads/' . $reservation->shop->file_name) }}" class="img-thumbnail">
									@else
										<img src="{{ asset('img/dummy.png') }}" class="img-thumbnail">
									@endif
								</a>
							</div>
							<div class="col-md-6 mb-2 mb-md-0">
								<h5 class="w-100">
									<a href="{{ route('shops.show', $reservation->shop->id) }}"
										class="link-dark ">店舗名：{{ $reservation->shop->name }}</a>
								</h5>
								<div>
									<small>来店日：{{ $reservation->formatted_visit_date }}</small><br>
									<small>来店時間：{{ $reservation->formatted_visit_time }}</small><br>
									<small>来店人数：{{ $reservation->number_of_people }}人</small><br>
									<small>電話番号：{{ $reservation->shop->formatted_phone_number }}</small><br>
									<small>住所：{{ $reservation->shop->address }}</small>
								</div>
							</div>
							<div class="col-md-2">
								<button class="button button--base button--danger button--sp" data-bs-toggle="modal"
									data-bs-target="#cancelReservationModal" data-reservation-id="{{ $reservation->id }}">
									取り消し
								</button>
							</div>
						</div>
						<hr class="my-3 my-md-4">
					@endforeach
				@endif

				{{ $reservations->links() }}
				<div class="modal fade" id="cancelReservationModal" tabindex="-1" aria-labelledby="cancelReservationModal"
					aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="cancelReservationModal">予約取り消し</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
							</div>
							<div class="modal-body">
								予約を取り消ししてもよろしいですか？<br>
							</div>
							<div class="modal-footer">
								<form id="cancel-form" data-base-url="{{ url('/') }}" action="#" method="POST">
									@csrf
									@method('PATCH')
									<button type="submit" class="button button--danger">予約を取り消し</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	@vite('resources/js/delete-modal.js')
@endsection
