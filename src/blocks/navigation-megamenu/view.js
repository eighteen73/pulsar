function calculateDistance(item) {
	const link = item.querySelector('.wp-block-navigation-item__content');
	const megamenu = item.querySelector(
		'.wp-block-pulsar-navigation-megamenu__container'
	);

	// If the link or menu is not found, return
	if (!link || !megamenu) {
		return;
	}

	// Get the position (top and bottom) of the link and menu
	const linkRect = link.getBoundingClientRect();
	const megamenuRect = megamenu.getBoundingClientRect();

	// Calculate the distance between the bottom of the link and the top of the menu
	const existingMargin = parseFloat(
		window.getComputedStyle(megamenu).marginTop || 0
	);
	const distance = megamenuRect.top - linkRect.bottom - existingMargin;

	// Set the CSS variable with the calculated distance
	item.style.setProperty('--megamenu-offset', `${distance}px`);
}

function adjustMegamenuPosition() {
	const megamenus = document.querySelectorAll(
		'.wp-block-pulsar-navigation-megamenu'
	);

	if (!megamenus.length) {
		return;
	}

	// Iterate through megamenus to calculate the distance or reset the margin-top
	megamenus.forEach((megamenu) => {
		calculateDistance(megamenu);
	});
}

function throttle(func, delay) {
	let timeoutId;
	return function (...args) {
		if (!timeoutId) {
			timeoutId = setTimeout(() => {
				func.apply(this, args);
				timeoutId = null;
			}, delay);
		}
	};
}

const throttledResizeHandler = throttle(adjustMegamenuPosition, 200);

// On reload and resize, adjust the megamenu position
window.addEventListener('DOMContentLoaded', adjustMegamenuPosition);
window.addEventListener('resize', throttledResizeHandler);
