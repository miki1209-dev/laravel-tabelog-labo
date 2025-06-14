function setupModalAction (modelId, buttonAttr, fromId, urlPrefix) {
	const modal = document.getElementById(modelId);
	if (!modal) return;
	modal.addEventListener('show.bs.modal', function (event) {
		const button = event.relatedTarget;
		const itemId = button.getAttribute(buttonAttr);

		const form = document.getElementById(fromId);
		const baseUrl = form.dataset.baseUrl;
		form.action = `${baseUrl}/${urlPrefix}/${itemId}`;
	});
}

document.addEventListener('DOMContentLoaded', () => {
	setupModalAction('cancelReservationModal', 'data-reservation-id', 'cancel-form', 'users/mypage/reservations');
	setupModalAction('deleteReviewModal', 'data-review-id', 'cancel-form', 'reviews');
});
