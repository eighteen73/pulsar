class Header {
	constructor() {
		this.element = document.querySelector('.site-header');
		this.lastScrollY = window.scrollY;
		this.ticking = false;
		this.offset = 150;
		this.deltaThreshold = 5;
	}

	init() {
		if (!this.element) {
			return;
		}

		this.observeHeaderHeight();
		this.updateHeight();
		window.addEventListener('load', () => this.updateHeight());
		window.addEventListener('resize', () => this.updateHeight());
		window.addEventListener('scroll', () => this.onScroll(), {
			passive: true,
		});
	}

	/**
	 * Observes the header element for size changes (e.g. fonts loading, content rendering).
	 * Keeps --pulsar--header--height in sync so menu positioning stays correct.
	 */
	observeHeaderHeight() {
		if (typeof ResizeObserver === 'undefined') {
			return;
		}
		const observer = new window.ResizeObserver(() => this.updateHeight());
		observer.observe(this.element);
	}

	onScroll() {
		if (!this.ticking) {
			window.requestAnimationFrame(() => {
				this.update();
				this.ticking = false;
			});

			this.ticking = true;
		}
	}

	update() {
		const doc = document.documentElement;
		const maxScroll = doc.scrollHeight - window.innerHeight;
		let currentScrollY = window.scrollY;

		// Clamp scroll to [0, maxScroll] so iOS rubber‑banding
		// at the top/bottom doesn't produce bogus values.
		if (currentScrollY < 0) {
			currentScrollY = 0;
		} else if (currentScrollY > maxScroll) {
			currentScrollY = maxScroll;
		}

		const delta = currentScrollY - this.lastScrollY;
		const { classList } = this.element;

		// At the very top: clear state and bail so overscroll
		// there doesn't cause pin/unpin flicker.
		if (currentScrollY <= 0) {
			classList.remove('is-scrolled', 'is-pinned', 'is-unpinned');
			this.lastScrollY = 0;
			return;
		}

		// Ignore tiny jitter
		if (Math.abs(delta) < this.deltaThreshold) {
			this.lastScrollY = currentScrollY;
			return;
		}

		// Add scrolled class once we're off the top
		classList.add('is-scrolled');

		// Pin/unpin header using clamped scroll position
		if (currentScrollY <= this.offset) {
			classList.remove('is-pinned', 'is-unpinned');
		} else if (currentScrollY > this.lastScrollY) {
			classList.remove('is-pinned');
			classList.add('is-unpinned');
		} else if (currentScrollY < this.lastScrollY) {
			classList.remove('is-unpinned');
			classList.add('is-pinned');
		}

		this.lastScrollY = currentScrollY;
	}

	updateHeight() {
		document.body.style.setProperty(
			'--pulsar--header--height',
			`${this.element.offsetHeight}px`
		);
	}
}

export default Header;
