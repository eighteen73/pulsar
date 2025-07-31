class Header {
	constructor() {
		this.element = document.querySelector('.site-header');
		this.lastScrollY = window.scrollY;
		this.ticking = false;
		this.offset = 150;
	}

	init() {
		if (!this.element) {
			return;
		}

		this.updateHeight();
		window.addEventListener('resize', () => this.updateHeight());
		window.addEventListener('scroll', () => this.onScroll(), {
			passive: true,
		});
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
		const currentScrollY = window.scrollY;
		const { classList } = this.element;

		// Add scrolled class
		if (currentScrollY > 0) {
			classList.add('is-scrolled');
		} else {
			classList.remove('is-scrolled');
		}

		// Pin/unpin header
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
