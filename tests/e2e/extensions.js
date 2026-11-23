/**
 * The paid WooCommerce extensions the extension tests cover. They're mapped
 * into wp-env locally (tests/wp-env/extensions.override.example.json); in CI
 * they're absent and their tests skip. (All Products for Subscriptions is part
 * of Subscriptions since 9.x.)
 */
const EXTENSIONS = [
	'woocommerce-subscriptions',
	'woocommerce-product-bundles',
	'woocommerce-bookings',
	'woocommerce-memberships',
];

/**
 * The extensions active on the tests site, by folder name.
 *
 * @param {Object} requestUtils WordPress request utils.
 * @return {Promise<string[]>} Active extensions.
 */
const activeExtensions = async (requestUtils) =>
	(
		await requestUtils.rest({
			path: '/wp/v2/plugins',
			params: { status: 'active' },
		})
	)
		.map(({ plugin }) => plugin.split('/')[0])
		.filter((folder) => EXTENSIONS.includes(folder));

module.exports = { EXTENSIONS, activeExtensions };
