@extends('layouts.app')
@section('content')
	<div class="container pt-5 pb-5">
		<div class="row justify-content-center">
			<div class="col-lg-7">

				<div class="mb-4">
					<h3 class="fw-bold">パスワード再設定</h3>
				</div>

				<hr class="mb-4">

				<form action="{{ route('password.store') }}" method="POST">
					@csrf
					<input type="hidden" name="token" value="{{ $request->route('token') }}">
					<div class="mb-3">
						<label for="email" class="form-label form__label">メールアドレス</label>
						<input id="email" type="email" name="email"
							class="form-control form__input form__input--muted @error('email') is-invalid @enderror"
							value="{{ old('email', $request->email) }}">
					</div>

					<div class="mb-3">
						<label for="password" class="form-label form__label">新しいパスワード</label>
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
						<label for="password-confirmation" class="form-label form__label">新しいパスワード（確認用）</label>
						<input id="password-confirmation" type="password" name="password_confirmation"
							class="form-control form__input form__input--muted">
					</div>
					<div class="d-flex justify-content-center">
						<button type="submit" class="button button--base w-50">パスワード再設定</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
