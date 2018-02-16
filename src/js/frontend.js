/* global window, document, localStorage */
((window, document) => {
	function init() {
		const banner = document.querySelector('#smart-plugin-banner');
		const body = document.querySelector('body');
		const closeButton = document.querySelector('.smart-plugin-banner__close');
		const activeClass = 'has-smart-plugin-banner--active';
		const customizerClass = 'has-smart-plugin-banner--customizer';
		const isCustomizer = body.classList.contains(customizerClass);
		const key = 'smart-plugin-banner-timestamp';
		const timeUntilExpire = 1209600000; // Two weeks in milliseconds.
		const timestamp = localStorage.getItem(key);

		if (
			!banner ||
			((timestamp + timeUntilExpire) > Date.now() && !isCustomizer)
		) {
			return;
		}

		if (closeButton) {
			closeButton.addEventListener('click', () => {
				body.classList.remove(activeClass);
				if (!isCustomizer) {
					localStorage.setItem(key, Date.now());
				}
			});
		}

		setTimeout(() => {
			body.classList.add(activeClass);
		}, 200);
	}

	// On DOM ready.
	document.addEventListener('DOMContentLoaded', () => {
		init();
	});
})(window, document);
