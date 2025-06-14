document.addEventListener('DOMContentLoaded', function() {
	const editModal = document.getElementById('editReviewModal');
	if (!editModal) return;

	editModal.addEventListener('show.bs.modal', function(event) {
		const button = event.relatedTarget;
		if (!button) return;
		const id = button.getAttribute('data-id');
		const title = button.getAttribute('data-title');
		const content = button.getAttribute('data-content');
		const score = button.getAttribute('data-score');
		const action = button.getAttribute('data-action');

		document.getElementById('edit-review-id').value = id;
		document.getElementById('edit-review-title').value = title;
		document.getElementById('edit-review-content').value = content;
		document.getElementById('edit-review-score').value = score;
		document.getElementById('edit-review-form').setAttribute('action', action);
	})

	if (editModal?.dataset.shouldOpen === 'true') {
		const modalInstance = new bootstrap.Modal(editModal);
		modalInstance.show();
	}
});
