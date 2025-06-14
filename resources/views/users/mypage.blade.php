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

				@if ($errors->any())
					<div class="flash-message flash-message--error">
						<i class="fas fa-exclamation-triangle"></i>
						<span>{{ $errors->first() }}</span>
					</div>
				@endif
				<div class="mb-3 mb-md-4">
					<div class="d-block d-md-flex align-items-md-center justify-content-md-between">
						<h3 class="fw-bold mb-1 mb-md-0">マイページ</h3>
						@if ($user->subscribed('premium'))
							<div>
								現在のステータス：
								<strong>有料会員</strong><br>
								@if ($subscriptionEndDays)
									<small>{{ $subscriptionEndDays }}までご利用可能</small>
								@endif
							</div>
						@else
							<div>現在のステータス：<strong>無料会員</strong></div>
						@endif
					</div>
				</div>
				<div class="mypage-menu">
					<div class="mypage-menu__item">
						<a href="{{ route('mypage.edit') }}" class="mypage-menu__link">
							<i class="fas fa-user mypage-menu__icon"></i>
							<div class="mypage-menu__text">会員情報の編集</div>
							<i class="fas fa-chevron-right mypage-menu__chevron"></i>
						</a>
					</div>

					@if (!$user->subscribed('premium'))
						<div class="mypage-menu__item">
							<a href="{{ route('mypage.subscription') }}" class="mypage-menu__link">
								<i class="fas fa-id-card mypage-menu__icon"></i>
								<div class="mypage-menu__text">有料会員登録</div>
								<i class="fas fa-chevron-right mypage-menu__chevron"></i>
							</a>
						</div>
					@else
						@if (is_null($subscription->ends_at))
							<div class="mypage-menu__item">
								<a href="#" class="mypage-menu__link" data-bs-toggle="modal" data-bs-target="#cancelConfirmModal">
									<i class="fas fa-door-open mypage-menu__icon"></i>
									<span class="mypage-menu__text">解約</span>
									<i class="fas fa-chevron-right mypage-menu__chevron"></i>
								</a>
							</div>
						@endif
						<div class="mypage-menu__item">
							<a href="{{ route('subscription.payment') }}" class="mypage-menu__link">
								<i class="fas fa-credit-card mypage-menu__icon"></i>
								<div class="mypage-menu__text">お支払い方法変更</div>
								<i class="fas fa-chevron-right mypage-menu__chevron"></i>
							</a>
						</div>

						<div class="mypage-menu__item">
							<a href="{{ route('mypage.reservations') }}" class="mypage-menu__link">
								<i class="fas fa-calendar-check mypage-menu__icon"></i>
								<div class="mypage-menu__text">予約一覧</div>
								<i class="fas fa-chevron-right mypage-menu__chevron"></i>
							</a>
						</div>

						<div class="mypage-menu__item">
							<a href="{{ route('mypage.favorites') }}" class="mypage-menu__link">
								<i class="fas fa-heart mypage-menu__icon"></i>
								<div class="mypage-menu__text">お気に入り</div>
								<i class="fas fa-chevron-right mypage-menu__chevron"></i>
							</a>
						</div>
					@endif

					<div class="mypage-menu__item">
						<a href="{{ route('logout') }}" class="mypage-menu__link"
							onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							<i class="fas fa-sign-out-alt mypage-menu__icon"></i>
							<span class="mypage-menu__text">ログアウト</span>
							<i class="fas fa-chevron-right mypage-menu__chevron"></i>
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
					</div>

				</div>
				<div class="modal fade" id="cancelConfirmModal" tabindex="-1" aria-labelledby="cancelConfirmModalLabel"
					aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="cancelConfirmModalLabel">解約の確認</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
							</div>
							<div class="modal-body">
								本当にサブスクリプションを解約してもよろしいですか？<br>
								<small>※契約が終了するまで、新たな有料会員登録はお待ちいただく必要があります。</small>
							</div>
							<div class="modal-footer">
								<form id="cancel-form" action="{{ route('subscription.cancel') }}" method="POST">
									@csrf
									<button type="submit" class="button button--base">はい、解約します</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
