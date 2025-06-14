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
					<h3 class="fw-bold">パスワード再設定</h3>
					<small>ご登録時のメールアドレスを入力してください。<br>パスワード再発行用のメールをお送りします。</small>
				</div>

				<hr class="mb-4">


				<form method="POST" action="{{ route('password.email') }}">
					@csrf
					<div class="form-group mb-4">
						<label for="email" class="form-label form__label">メールアドレス</label>
						<input id="email" type="email"
							class="form-control form__input form__input--muted @error('email') is-invalid @enderror" name="email"
							value="{{ old('email') }}">
						@error('email')
							<span class="invalid-feedback">
								<strong>{{ $message }}</strong>
							</span>
						@enderror
					</div>
					<div class="d-flex justify-content-center align-items-center mb-4">
						<button type="submit" class="button button--base w-50">送信</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
