@extends('layouts.app')
@section('content')
	<div class="container pt-5 pb-5">
		<div class="row justify-content-center">
			<div class="col-lg-7">

				@if (session('status'))
					<div class="flash-message flash-message--success">
						{{ session('status') }}
					</div>
				@endif

				<div class="mb-4">
					<h3 class="fw-bold">ログイン</h3>
				</div>

				<hr class="mb-4">

				<form action="{{ route('login') }}" method="POST">
					@csrf
					<div class="mb-4">
						<label for="email" class="form-label form__label">メールアドレス</label>
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
						<label for="password" class="form-label form__label">パスワード</label>
						<input id="password" type="password" name="password"
							class="form-control form__input form__input--muted @error('password') is-invalid @enderror">
						@error('password')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="form-group mb-4">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="remember" id="remember">
							<label for="remember" class="form-check-label w-100">
								次回から自動的にログインする
							</label>
						</div>
					</div>
					<div class="d-flex justify-content-center align-items-center mb-4">
						<button type="submit" class="button button--base w-50">ログイン</button>
					</div>
				</form>
				<div class="text-center mb-3">
					<a class="fw-bold link--hover" href="{{ route('password.request') }}">
						パスワードをお忘れの場合
						<i class="fas fa-chevron-right ms-2"></i>
					</a>
				</div>
				<div class="text-center">
					<a class="fw-bold link--hover" href="{{ route('register') }}">
						新規会員登録
						<i class="fas fa-chevron-right ms-2"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
@endsection
