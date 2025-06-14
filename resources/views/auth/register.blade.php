@extends('layouts.app')
@section('content')
	<div class="container pt-5 pb-5">
		<div class="row justify-content-center">
			<div class="col-lg-7">

				@if ($errors->has('db_error') || $errors->has('general_error'))
					<div class="alert alert-danger">
						{{ $errors->first('db_error') ?? $errors->first('general_error') }}
					</div>
				@endif

				<div class="mb-4">
					<h3 class="fw-bold">新規登録</h3>
					<small>※「必須」ラベルがついていない箇所は未入力でも問題ありません</small>
				</div>

				<hr class="mb-4">

				<form method="POST" action="{{ route('register') }}">
					@csrf
					<div class="mb-3">
						<label for="name" class="form-label form__label">名前<span
								class="ms-2 required-mark required-mark--required">必須</span></label>
						<input id="name" type="text" name="name"
							class="form-control form__input form__input--muted @error('name') is-invalid @enderror"
							value="{{ old('name') }}" autocomplete="name">
						@error('name')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-3">
						<label for="email" class="form-label form__label">メールアドレス<span
								class="ms-2 required-mark required-mark--required">必須</span></label>
						<input id="email" type="email" name="email"
							class="form-control form__input form__input--muted @error('email') is-invalid @enderror"
							value="{{ old('email') }}">
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
							value="{{ old('postal_code') }}">
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
							value="{{ old('address') }}">
						@error('address')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-3">
						<label for="phone" class="form-label form__label">電話番号</label>
						<input id="phone" type="tel" name="phone"
							class="form-control form__input form__input--muted @error('phone') is-invalid @enderror"
							value="{{ old('phone') }}">
						@error('phone')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-3">
						<label for="password" class="form-label form__label">パスワード<span
								class="ms-2 required-mark required-mark--required">必須</span></label>
						<input id="password" type="password" name="password"
							class="form-control form__input form__input--muted @error('password') is-invalid @enderror">
						<small class="form-text text-muted">※パスワードは8文字以上で入力してください。</small>
						@error('password')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="mb-4">
						<label for="password-confirmation" class="form-label form__label">パスワード（確認用）<span
								class="ms-2 required-mark required-mark--required">必須</span></label>
						<input id="password-confirmation" type="password" name="password_confirmation"
							class="form-control form__input form__input--muted">
					</div>
					<input type="hidden" name="role" value="free">
					<div class="d-flex justify-content-center">
						<button type="submit" class="button button--base w-50">アカウント作成</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
