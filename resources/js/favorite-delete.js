document.addEventListener('DOMContentLoaded', function () {
	const deleteButtons = document.querySelectorAll('.favorite-delete-button');
	const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
	const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');

	deleteButtons.forEach(button => {
		button.addEventListener('click', async function () {
			const shopId = this.dataset.shopId;

			try {
				const response = await fetch(`${baseUrl}/api/favorites/${shopId}`, {
					method: 'DELETE',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': token,
						'Accept': 'application/json',
					},
					credentials: 'same-origin',
				});

				if (response.ok) {
					this.closest('.col-md-2').parentElement.remove();
				} else {
					const data = await response.json();
					alert(data.message || '削除に失敗しました');
				}
			} catch (error) {
				console.error('通信エラー:', error);
				alert('通信に失敗しました');
			}
		});
	});
});
