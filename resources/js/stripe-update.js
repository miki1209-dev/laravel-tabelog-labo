import { loadStripe } from '@stripe/stripe-js';

document.addEventListener('DOMContentLoaded', async () => {
	const stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY);
	const elements = stripe.elements();

	const cardElement = elements.create('card', {
		hidePostalCode: true,
		disableLink: true
	});
	cardElement.mount('#card-element');

	const form = document.getElementById('update-form');
	const cardholderName = document.getElementById('cardholder-name');

	form.addEventListener('submit', async (e) => {
		e.preventDefault();

		const errorName = document.getElementById('name-errors');
		const errorCard = document.getElementById('card-errors');
		errorName.style.display = 'none';
		errorName.innerHTML = '';
		errorCard.style.display = 'none';
		errorCard.innerHTML = '';

		let hasError = false;

		if (!cardholderName.value.trim()) {
			const nameError = document.createElement('div');
			nameError.textContent = 'カード名義を入力してください';
			errorName.appendChild(nameError);
			hasError = true;
		}

		const result = await stripe.createPaymentMethod({
			type: 'card',
			card: cardElement,
			billing_details: {
				name: cardholderName.value,
			}
		});
		const paymentMethod = result.paymentMethod;
		const error = result.error;

		if (error) {
			const stripeError = document.createElement('div');
			stripeError.textContent = result.error.message;
			errorCard.appendChild(stripeError);
			hasError = true;
		}

		if (hasError) {
			errorName.style.display = 'block';
			errorCard.style.display = 'block';
			return;
		}

		const hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', paymentMethod.id);
		form.appendChild(hiddenInput);

		form.submit();
	});
});
