@extends('layouts.app')
@section('content')
	<div class="container py-4 py-md-5">
		<div class="row justify-content-center align-items-center">
			<div class="col-lg-8 text-center">
				<h3 class="mb-3">ご予約ありがとうございます！</h3>
				<p class="mb-4">予約が正常に完了しました。</p>

				<div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
					<a href="{{ route('top') }}" class="button button--base text-center">トップページへ</a>
					<a href="{{ route('mypage.reservations') }}" class="button button--base text-center">予約一覧へ</a>
				</div>
			</div>
		</div>
	</div>
@endsection
