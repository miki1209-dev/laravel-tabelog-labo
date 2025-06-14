document.addEventListener('DOMContentLoaded', function () {
	const buttons = document.querySelectorAll('.favorite-button');

	buttons.forEach(button => {
		button.addEventListener('click', async function() {
			const shopId = this.dataset.shopId;
			const isFavorited = this.dataset.favorited === 'true';
			const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
			const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');

			try {
				let response;

				if (!isFavorited) {
					response = await fetch(`${baseUrl}/api/favorites`, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': token,
							'Accept': 'application/json',
						},
						body: JSON.stringify({ shop_id: shopId }),
						credentials: 'same-origin',
					});
				} else {
					response = await fetch(`${baseUrl}/api/favorites/${shopId}`, {
						method: 'DELETE',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': token,
							'Accept': 'application/json',
						},
						credentials: 'same-origin',
					});
				}

				const data = await response.json();
				if (response.ok) {
					this.dataset.favorited = (!isFavorited).toString();
					if (!isFavorited) {
						this.innerHTML = '<i class="fas fa-heart-broken"></i>';
					} else {
						this.innerHTML = '<i class="far fa-heart"></i>';
					}
				} else {
					alert(data.message || 'エラーが発生しました');
				}
			} catch (error) {
				console.error('通信エラー:', error);
				alert('サーバーとの通信に失敗しました');
			}
		})
	})
});
