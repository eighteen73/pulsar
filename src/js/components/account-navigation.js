/**
 * My Account's navigation: below lg it's a strip of tabs that scrolls
 * sideways (woocommerce-account.scss). Bring the current section's tab into
 * view, so it's never off the edge.
 */
export default function accountNavigation() {
	const list = document.querySelector('.woocommerce-MyAccount-navigation ul');
	const active = list?.querySelector('.is-active');

	if (!active || list.scrollWidth <= list.clientWidth) {
		return;
	}

	const offset =
		active.getBoundingClientRect().left - list.getBoundingClientRect().left;

	list.scrollLeft += offset - (list.clientWidth - active.offsetWidth) / 2;
}
