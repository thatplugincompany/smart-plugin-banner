/* global window, document, localStorage */
((window, document) => {
	function init() {
		const banner = document.querySelector('#smart-plugin-banner');
		const body = document.querySelector('body');
		const closeButton = document.querySelector('.smart-plugin-banner__close');
		const activeClass = 'smart-plugin-banner--active';
		const activeBodyClass = 'has-smart-plugin-banner--active';
		const key = 'smart-plugin-banner-expiration';
		const timeUntilExpire = 1209600000; // Two weeks in milliseconds.
		const expirationDate = localStorage.getItem(key);

		if (!banner || expirationDate > Date.now()) {
			return;
		}

		if (closeButton) {
			closeButton.addEventListener('click', () => {
				banner.classList.remove(activeClass);
				localStorage.setItem(key, Date.now() + timeUntilExpire);
			});
		}

		setTimeout(() => {
			banner.classList.add(activeClass);
			body.classList.add(activeBodyClass);
		}, 200);
	}

	// On DOM ready.
	document.addEventListener('DOMContentLoaded', () => {
		init();
	});
})(window, document);
