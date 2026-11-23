/**
 * The basket as WooCommerce has saved it, for tests that add to it and then
 * open the basket or checkout.
 */
const { expect } = require('@wordpress/e2e-test-utils-playwright');

// The basket and checkout blocks draw in the browser after the page loads:
// about 3 seconds on a warm site, longer on a cold one (as CI's always is).
const BASKET_TIMEOUT = 15_000;

/**
 * Wait until WooCommerce has saved the basket. Adding to it updates the mini
 * cart's badge from the Store API's response, but the basket is saved to the
 * session at the end of that request: on a cold site the next page can load
 * before then and find the basket empty.
 *
 * @param {import('@playwright/test').Page} page Page.
 */
async function basketSaved(page) {
	await expect
		.poll(
			async () => {
				const response = await page.request.get(
					'/wp-json/wc/store/v1/cart'
				);

				return (await response.json()).items_count ?? 0;
			},
			{ timeout: BASKET_TIMEOUT }
		)
		.toBeGreaterThan(0);
}

module.exports = { BASKET_TIMEOUT, basketSaved };
