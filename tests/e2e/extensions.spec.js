/**
 * WooCommerce's paid extensions: Subscriptions (with All Products for
 * Subscriptions), Product Bundles, Bookings and Memberships. Run locally with
 * the extensions mapped in (tests/wp-env/extensions.override.example.json);
 * each test skips when its extension isn't active, as in CI. Content comes
 * from tests/wp-env/seed-extensions.php.
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const AxeBuilder = require('@axe-core/playwright').default;
const { activeExtensions } = require('./extensions');

const AXE_TAGS = ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'];
const CUSTOMER = { username: 'pulsarext', password: 'Pulsar-ext-1' };

// As a visitor, failing on any uncaught script error.
test.use({
	storageState: { cookies: [], origins: [] },
	viewport: { width: 1280, height: 900 },
});

let active = [];

test.beforeAll(async ({ requestUtils }) => {
	active = await activeExtensions(requestUtils);
});

const scriptErrors = new WeakMap();

test.beforeEach(({ page }) => {
	scriptErrors.set(page, []);
	page.on('pageerror', (error) => scriptErrors.get(page).push(error.message));
});

test.afterEach(({ page }) => {
	expect(scriptErrors.get(page), 'uncaught script errors').toEqual([]);
});

/**
 * Skip the test unless the extension is active.
 *
 * @param {string} extension The extension's folder name.
 */
const requires = (extension) =>
	test.skip(!active.includes(extension), `${extension} isn't active`);

const violations = async (page) =>
	(
		await new AxeBuilder({ page }).withTags(AXE_TAGS).analyze()
	).violations.map(
		({ id, nodes }) =>
			`${id}: ${nodes.map((node) => node.target).join(' | ')}`
	);

/**
 * Log in as the seeded customer (a subscriber, member and booker).
 *
 * @param {Object} page Page.
 */
const logIn = async (page) => {
	await page.goto('/my-account/');
	await page.locator('#username').fill(CUSTOMER.username);
	await page.locator('#password').fill(CUSTOMER.password);
	await page.getByRole('button', { name: /log in/i }).click();
	await expect(page.locator('.my-account-header__user')).toBeVisible();
};

/**
 * Add the product on the page to the basket, waiting for the mini cart to
 * count it.
 *
 * @param {Object} page Page.
 */
const addToBasket = async (page) => {
	await page
		.locator('form.cart')
		.getByRole('button', { name: /add to (basket|cart)|book now/i })
		.click();
	await expect(page.locator('.wc-block-mini-cart__badge')).toHaveText(
		/[1-9]/
	);
};

test.describe('Subscriptions', () => {
	test('subscribe and save is a choice of cards, and the basket and checkout show the recurring totals', async ({
		page,
	}) => {
		requires('woocommerce-subscriptions');

		await page.goto('/product/pulsar-ext-coffee/');

		const options = page.locator('.wcsatt-options-wrapper');
		const plans = page.locator('.wcsatt-options-product-dropdown');

		await expect(options.getByRole('radio')).toHaveCount(2);
		await expect(plans).toBeHidden();
		expect(await violations(page)).toEqual([]);

		await options.getByText(/subscribe to save/i).click();
		await expect(plans).toBeVisible();
		await plans.selectOption('2_month');
		// (Once the plans have slid open: mid-slide, they overlap the button.)
		await page.waitForFunction(() => !window.jQuery(':animated').length);
		expect(await violations(page)).toEqual([]);

		await addToBasket(page);

		for (const path of ['/cart/', '/checkout/']) {
			await page.goto(path);
			await expect(
				page.locator('.wcs-recurring-totals-panel').first(),
				path
			).toContainText(/every 2 months/i);
			expect(await violations(page), path).toEqual([]);
		}
	});

	test("a customer's subscriptions, and one subscription, pass axe", async ({
		page,
	}) => {
		requires('woocommerce-subscriptions');

		await logIn(page);
		await page.goto('/my-account/subscriptions/');
		await expect(page.locator('.my-account-title')).toBeVisible();
		expect(await violations(page)).toEqual([]);

		await page
			.locator('.woocommerce-orders-table__cell-subscription-id a')
			.first()
			.click();
		await expect(page).toHaveURL(/view-subscription/);
		await expect(
			page.locator('.subscription_details .button.cancel')
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});
});

test.describe('Product Bundles', () => {
	test("a bundle's items show on its page, and a fixed item's quantity isn't a stepper in the basket", async ({
		page,
	}) => {
		requires('woocommerce-product-bundles');

		await page.goto('/product/pulsar-ext-bundle/');
		await expect(page.locator('.bundled_product')).toHaveCount(2);
		expect(await violations(page)).toEqual([]);

		await addToBasket(page);
		await page.goto('/cart/');

		const bundled = page.locator('.wc-block-cart-items__row.is-bundled');

		await expect(bundled).toContainText('Ext tee');
		await expect(
			bundled.getByRole('button', { name: /increase/i })
		).toHaveCount(0);
		expect(await violations(page)).toEqual([]);
	});
});

test.describe('Bookings', () => {
	test('a booking shows its cost once a day and time are chosen', async ({
		page,
	}) => {
		requires('woocommerce-bookings');

		await page.goto('/product/pulsar-ext-workshop/');

		const form = page.locator('.wc-bookings-booking-form');

		// A day next month: the first days that can be booked are a day ahead,
		// and their earliest times can slip under that as the day goes on.
		await form.locator('.ui-datepicker-next').click();
		await form
			.locator(
				'.ui-datepicker-calendar td:not(.ui-datepicker-unselectable):not(.ui-datepicker-other-month) a'
			)
			.first()
			.click();
		await form.locator('.block-picker a').first().click();
		await expect(form.locator('.wc-bookings-booking-cost')).toContainText(
			/cost/i
		);
		expect(await violations(page)).toEqual([]);

		await addToBasket(page);
		await page.goto('/cart/');
		await expect(
			page.locator('.wc-block-components-product-details')
		).toContainText(/booking date/i);
		expect(await violations(page)).toEqual([]);
	});

	test("a customer's bookings pass axe", async ({ page }) => {
		requires('woocommerce-bookings');

		await logIn(page);
		await page.goto('/my-account/bookings/');
		await expect(page.locator('.my-account-title')).toBeVisible();
		await expect(page.getByText('Ext workshop').first()).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});
});

test.describe('Memberships', () => {
	test("a members' page is restricted to visitors, and readable by a member", async ({
		page,
	}) => {
		requires('woocommerce-memberships');

		await page.goto('/pulsar-ext-members-only/');
		await expect(
			page.locator('.wc-memberships-restriction-message')
		).toBeVisible();
		await expect(page.getByText('Only members can read this.')).toHaveCount(
			0
		);
		expect(await violations(page)).toEqual([]);

		await logIn(page);
		await page.goto('/pulsar-ext-members-only/');
		await expect(
			page.getByText('Only members can read this.')
		).toBeVisible();
	});

	test("a member's area and member price pass axe", async ({ page }) => {
		requires('woocommerce-memberships');

		await logIn(page);
		await page.goto('/my-account/members-area/');
		await expect(page.locator('.my-account-title')).toBeVisible();
		await expect(page.getByText('Ext members club').first()).toBeVisible();
		expect(await violations(page)).toEqual([]);

		await page.goto('/product/pulsar-ext-mug/');
		await expect(
			page.locator('.wc-block-components-product-price').first()
		).toContainText('7.20');
		expect(await violations(page)).toEqual([]);
	});
});
