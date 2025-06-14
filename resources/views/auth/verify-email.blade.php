@extends('layouts.app')
@section('content')
	<div class="container pt-5 pb-5">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<h3 class="text-center fw-bold mb-3">会員登録ありがとうございます！</h3>
				<p class="text-center mb-4">現在、仮会員の状態です。ただいま、ご入力頂いたメールアドレス宛に
					<br>ご本人様確認用のメールをお送りしました。
					<br>メール本文内のURLをクリックすると本会員登録が完了となります。
				</p>
				<div class="d-flex justify-content-center align-items-center mb-4">
					<a href="{{ url('/') }}" class="button button--create w-50 text-center">トップページへ</a>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection
