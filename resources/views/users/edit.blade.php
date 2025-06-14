@extends('layouts.app')
@section('content')
	<div class="container py-4 py-md-5">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				@if ($errors->has('db_error') || $errors->has('general_error'))
					<div class="alert alert-light">
						{{ $errors->first('db_error') ?? $errors->first('general_error') }}
					</div>
				@endif
				<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
					<ol class="breadcrumb mb-1 mb-md-2">
						<li class="breadcrumb-item"><a href="{{ route('mypage') }}">マイページ</a></li>
						<li class="breadcrumb-item active" aria-current="page">会員情報編集</li>
					</ol>
				</nav>

				<div class="mb-2 mb-md-4">
					<h3>会員情報編集</h3>
				</div>

				<hr class="my-3 my-md-4">

				<form action="{{ route('mypage.update') }}" method="POST" onkeydown="return event.key !== 'Enter';">
					@csrf
					@method('PUT')
					<div class="mb-3">
						<label for="name" class="form-label form__label">名前<span
								class="ms-2 required-mark required-mark--required">必須</span></label>
						<input id="name" type="text" name="name"
							class="form-control form__input form__input--muted @error('name') is-invalid @enderror"
							value="{{ $user->name }}" autocomplete="name">
						@error('name')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-3">
						<label for="email" class="form-label form__label">メールアドレス<span
								class="ms-2 required-mark required-mark--required">必須</span></label>
						<input id="email" type="text" name="email"
							class="form-control form__input form__input--muted @error('email') is-invalid @enderror"
							value="{{ $user->email }}">
						@error('email')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-3">
						<label for="postal_code" class="form-label form__label">郵便番号</label>
						<input id="postal_code" type="text" name="postal_code"
							class="form-control form__input form__input--muted @error('postal_code') is-invalid @enderror"
							value="{{ $user->postal_code }}">
						@error('postal_code')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-3">
						<label for="address" class="form-label form__label">住所</label>
						<input id="address" type="text" name="address"
							class="form-control form__input form__input--muted @error('address') is-invalid @enderror"
							value="{{ $user->address }}">
						@error('address')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-4">
						<label for="phone" class="form-label form__label">電話番号</label>
						<input id="phone" type="text" name="phone"
							class="form-control form__input form__input--muted @error('phone') is-invalid @enderror"
							value="{{ $user->phone }}">
						@error('phone')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="d-flex justify-content-center">
						<button type="submit" class="button button--base w-50 mb-4 mb-md-3">更新</button>
					</div>
					<div class="d-flex justify-content-center">
						<a href="{{ route('mypage') }}" class="text-center">キャンセル</a>
					</div>
				</form>
				<hr class="mt-4">
				<div class="d-flex justify-content-center">
					<button type="button" class="button button--base button--danger" data-bs-toggle="modal"
						data-bs-target="#deleteUserConfirmModal">退会する</button>
				</div>

				<div class="modal fade" id="deleteUserConfirmModal" tabindex="-1" aria-labelledby="deleteUserConfirmModalLabel">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title fw-bold" id="deleteUserConfirmModalLabel">本当に退会しますか？</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
							</div>
							<div class="modal-body">
								<p class="text-center mb-0">
									一度退会するとデータはすべて削除され、<br>復旧はできません。<br><small>※有料会員の方は退会後、プランは自動で解約されます</small></p>

							</div>
							<form action="{{ route('mypage.destroy') }}" method="POST">
								@csrf
								@method('DELETE')
								<div class="modal-footer">
									<button type="submit" class="button button--base button--danger">退会する</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
