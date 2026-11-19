/**
 * Front end, as a visitor: sections in every scheme, the header and footer,
 * archives and WooCommerce. Content comes from content.js.
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const AxeBuilder = require('@axe-core/playwright').default;
const { SCHEMES, SETTINGS, PAGE_PREFIX, COUPON } = require('./content');

const DESKTOP = { width: 1280, height: 900 };
const MOBILE = { width: 390, height: 800 };
const AXE_TAGS = ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'];

// As a visitor, failing on any uncaught script error.
test.use({ storageState: { cookies: [], origins: [] }, viewport: DESKTOP });

const scriptErrors = new WeakMap();

test.beforeEach(({ page }) => {
	scriptErrors.set(page, []);
	page.on('pageerror', (error) => scriptErrors.get(page).push(error.message));
});

test.afterEach(({ page }) => {
	expect(scriptErrors.get(page), 'uncaught script errors').toEqual([]);
});

const violations = async (page, disableRules = []) =>
	(
		await new AxeBuilder({ page })
			.withTags(AXE_TAGS)
			.disableRules(disableRules)
			.analyze()
	).violations.map(
		({ id, nodes }) =>
			`${id}: ${nodes.map((node) => node.target).join(' | ')}`
	);

test.describe('sections', () => {
	for (const scheme of SCHEMES) {
		test(`every section in the ${scheme} scheme passes axe`, async ({
			page,
		}) => {
			const response = await page.goto(
				`/${PAGE_PREFIX}sections-${scheme}/`
			);

			expect(response.status()).toBe(200);
			await expect(
				page.getByRole('heading', {
					level: 1,
					name: `Sections in the ${scheme} scheme`,
				})
			).toBeVisible();
			await expect(
				page.locator(`.wp-block-pulsar-hero.has-scheme-${scheme}`)
			).toBeVisible();
			expect(await violations(page)).toEqual([]);
		});
	}

	test("a section's own padding, at one class, beats the shared band padding", async ({
		page,
	}) => {
		await page.goto(`/${PAGE_PREFIX}sections-default/`);

		// A band of the test's own, with no section's class, so a project's
		// section styles don't take part. The rule is as a block stylesheet's:
		// one class, loaded before the theme's.
		await page.evaluate(() => {
			const style = document.createElement('style');
			const band = document.createElement('section');

			style.textContent = '.is-padding-test { padding-block: 3px; }';
			document.head.prepend(style);
			band.className = 'is-section alignfull is-padding-band';
			document.querySelector('.wp-block-post-content').append(band);
		});

		const band = page.locator('.is-padding-band');

		// The shared padding applies (the bottom: the top may collapse
		// against the band above).
		await expect(band).not.toHaveCSS('padding-bottom', '0px');
		await expect(band).not.toHaveCSS('padding-bottom', '3px');

		await band.evaluate((element) =>
			element.classList.add('is-padding-test')
		);

		await expect(band).toHaveCSS('padding-top', '3px');
		await expect(band).toHaveCSS('padding-bottom', '3px');
	});

	test('a linked card links from its heading', async ({ page }) => {
		await page.goto(`/${PAGE_PREFIX}sections-default/`);

		await expect(
			page.getByRole('link', { name: 'Card 1' })
		).toHaveAttribute('href', '/');
	});
});

test.describe('Text section alignments', () => {
	test('wide blocks take the wide width, and full ones the whole band', async ({
		page,
	}) => {
		await page.setViewportSize({ width: 1700, height: 900 });
		await page.goto(`/${PAGE_PREFIX}sections-default/`);

		const widths = await page.evaluate(() => {
			const content = document.querySelector(
				'.is-aligned-test .wp-block-pulsar-text__content'
			);
			const box = (element) => element.getBoundingClientRect();

			return {
				viewport: document.documentElement.clientWidth,
				overflow:
					document.documentElement.scrollWidth -
					document.documentElement.clientWidth,
				paragraph: box(content.querySelector(':scope > p')).width,
				wide: box(content.querySelector(':scope > .alignwide')).width,
				full: box(content.querySelector(':scope > .alignfull')),
			};
		});

		expect(widths.paragraph).toBe(768);
		expect(widths.wide).toBe(1440);
		expect(widths.full.left).toBeLessThanOrEqual(0);
		expect(widths.full.right).toBeGreaterThanOrEqual(widths.viewport);
		expect(widths.overflow).toBe(0);
	});
});

test.describe('header and footer', () => {
	test('desktop: the menu opens a submenu, and the call to action shows', async ({
		page,
	}) => {
		await page.goto(`/${PAGE_PREFIX}sections-default/`);

		const header = page.locator('.site-header');
		const services = header
			.locator('.matter-menu__item.has-submenu')
			.first();

		await services.hover();
		await expect(services).toHaveClass(/\bis-open\b/);
		await expect(
			services.getByRole('link', { name: 'Design' })
		).toBeVisible();

		await expect(
			header.getByRole('link', { name: SETTINGS.header.cta_label })
		).toHaveAttribute('href', SETTINGS.header.cta_url);
	});

	test('mobile: the menu panel opens, traps focus and closes on Escape', async ({
		page,
	}) => {
		await page.setViewportSize(MOBILE);
		await page.goto(`/${PAGE_PREFIX}sections-default/`);

		const toggle = page.locator('[aria-controls="site-navigation"]');
		const panel = page.locator('#site-navigation');

		await toggle.click();
		await expect(panel).toHaveClass(/\bis-open\b/);
		await expect(
			panel.getByRole('button', { name: /Services/ })
		).toBeVisible();
		await expect
			.poll(() =>
				panel.evaluate((element) =>
					element.contains(element.ownerDocument.activeElement)
				)
			)
			.toBe(true);

		await page.keyboard.press('Escape');
		await expect(panel).not.toHaveClass(/\bis-open\b/);
		await expect(toggle).toBeFocused();
	});

	test('the footer shows contact details, menus and the copyright', async ({
		page,
	}) => {
		await page.goto(`/${PAGE_PREFIX}sections-default/`);

		const footer = page.locator('.site-footer');

		await expect(
			footer.getByRole('link', { name: SETTINGS.contact.phone })
		).toHaveAttribute('href', 'tel:01134960000');
		await expect(
			footer.getByRole('link', { name: 'Privacy policy' })
		).toBeVisible();
		await expect(
			footer.getByRole('link', { name: 'Careers' })
		).toBeVisible();
		await expect(footer.locator('.site-footer__copyright')).toContainText(
			String(new Date().getFullYear())
		);
	});

	test('the footer meets a page that ends in a coloured band, and keeps its gap otherwise', async ({
		page,
	}) => {
		const gap = async () =>
			page.evaluate(() => {
				const footer = document.querySelector('.site-footer');
				const main = document.querySelector('.site-main');

				return Math.round(
					footer.getBoundingClientRect().top -
						main.getBoundingClientRect().bottom
				);
			});

		await page.goto(`/${PAGE_PREFIX}sections-subtle/`);
		expect(await gap()).toBe(0);

		await page.goto('/product/test-bauble/');
		expect(await gap()).toBeGreaterThan(0);
	});
});

test.describe('archives, search and 404', () => {
	test('the blog lists posts under its title', async ({ page }) => {
		await page.goto('/');

		await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
		await expect(
			page.getByRole('link', { name: 'News story 1' })
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});

	test('a category shows its name and its posts', async ({ page }) => {
		await page.goto('/category/news/');

		await expect(
			page.getByRole('heading', { level: 1, name: 'News' })
		).toBeVisible();
		await expect(
			page.getByRole('link', { name: 'News story 2' })
		).toBeVisible();
	});

	test('search shows its results', async ({ page }) => {
		await page.goto('/?s=story');

		await expect(page.getByRole('heading', { level: 1 })).toContainText(
			'story'
		);
		await expect(
			page.getByRole('link', { name: 'News story 3' })
		).toBeVisible();
	});

	test('a missing page is a 404 with its content', async ({ page }) => {
		const response = await page.goto('/this-page-does-not-exist/');

		expect(response.status()).toBe(404);
		await expect(
			page.getByRole('heading', { level: 1, name: 'Page not found' })
		).toBeVisible();
	});
});

test.describe('WooCommerce', () => {
	test('the shop lists products with their loop item', async ({ page }) => {
		await page.goto('/shop/');

		await expect(
			page.getByRole('link', { name: 'Test bauble' }).first()
		).toBeVisible();
		await expect(
			page.locator('.wp-block-matter-loop-item').first()
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});

	test('a product category lists its products', async ({ page }) => {
		await page.goto('/product-category/christmas/');

		await expect(
			page.getByRole('heading', { level: 1, name: 'Christmas' })
		).toBeVisible();
		await expect(
			page.getByRole('link', { name: 'Test wreath' }).first()
		).toBeVisible();
	});

	test("the mini-cart opens, and can't be tabbed into while closed", async ({
		page,
	}) => {
		await page.goto(`/${PAGE_PREFIX}sections-default/`);

		const drawer = page.locator('.wc-block-mini-cart__drawer');
		const close = drawer.locator('.wc-block-components-drawer__close');

		await expect(close).toBeHidden();
		await page.locator('.wc-block-mini-cart__button').click();
		await expect(close).toBeVisible();
		await close.click();
		await expect(close).toBeHidden();
	});

	test('a product can be added to the basket, and the basket and checkout load', async ({
		page,
	}) => {
		await page.goto('/product/test-bauble/');
		await expect(
			page.getByRole('heading', { level: 1, name: 'Test bauble' })
		).toBeVisible();
		await page
			.getByRole('button', { name: /add to (basket|cart)/i })
			.first()
			.click();
		await expect(page.locator('.wc-block-mini-cart__badge')).toHaveText(
			/[1-9]/
		);

		await page.goto('/cart/');
		await expect(page.getByText('Test bauble').first()).toBeVisible();

		await page.goto('/checkout/');
		await expect(
			page.locator('.wp-block-woocommerce-checkout')
		).toBeVisible();
	});

	test("a link to #reviews opens the product's reviews", async ({ page }) => {
		await page.goto('/product/test-bauble/#reviews');

		// WooCommerce's reviews title is the target; nothing else has the ID.
		await expect(page.locator('[id="reviews"]')).toHaveCount(1);

		await expect(
			page.locator('.wp-block-matter-accordion-item').filter({
				has: page.locator('#reviews'),
			})
		).toHaveClass(/\bis-open\b/);
		await expect(
			page.getByRole('button', { name: 'Submit' })
		).toBeVisible();
	});

	test('the customer payment page shows the order and how to pay', async ({
		page,
		requestUtils,
	}) => {
		await requestUtils.rest({
			path: '/wc/v3/payment_gateways/bacs',
			method: 'PUT',
			data: { enabled: true },
		});

		const [product] = await requestUtils.rest({
			path: '/wc/v3/products',
			params: { search: 'Test bauble' },
		});
		const order = await requestUtils.rest({
			path: '/wc/v3/orders',
			method: 'POST',
			data: {
				status: 'pending',
				billing: { email: 'payer@example.com' },
				line_items: [{ product_id: product.id, quantity: 1 }],
			},
		});

		await page.goto(
			`/checkout/order-pay/${order.id}/?pay_for_order=true&key=${order.order_key}`
		);

		await expect(page.locator('#payment')).toBeVisible();
		await expect(
			page.getByRole('button', { name: /pay for order/i })
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});
});

test.describe('WooCommerce: stock, filters, basket and checkout', () => {
	/**
	 * Add a product to the basket from its page, waiting for the mini cart to
	 * count it.
	 *
	 * @param {Object} page Page.
	 * @param {string} slug The product's slug.
	 */
	const addToBasket = async (page, slug) => {
		await page.goto(`/product/${slug}/`);
		await page
			.locator('.wc-block-add-to-cart-with-options')
			.getByRole('button', { name: /add to (basket|cart)/i })
			.click();
		await expect(page.locator('.wc-block-mini-cart__badge')).toHaveText(
			/[1-9]/
		);
	};

	test("a product shows its stock, and out of stock it can't be bought", async ({
		page,
	}) => {
		for (const [slug, stock] of [
			['test-back-order', /back-?order/i],
			['test-few-left', '3 in stock'],
			['test-out-of-stock', 'Out of stock'],
		]) {
			await page.goto(`/product/${slug}/`);
			await expect(
				page.locator('.wc-block-components-product-stock-indicator')
			).toContainText(stock);
		}

		// (Related products below can still be added.)
		await expect(
			page
				.locator('.wc-block-add-to-cart-with-options')
				.getByRole('button', { name: /add to (basket|cart)/i })
		).toHaveCount(0);
		expect(await violations(page)).toEqual([]);
	});

	test('a product with reviews shows its rating and reviews, and passes axe', async ({
		page,
	}) => {
		await page.goto('/product/test-bauble/#reviews');

		await expect(
			page.locator('.wp-block-woocommerce-product-review-rating').first()
		).toBeVisible();
		await expect(page.getByText('Lovely and sparkly.')).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});

	test('the product filters narrow the products without a page load, and pass axe', async ({
		page,
	}) => {
		await page.goto(`/${PAGE_PREFIX}filters/`);

		await expect(
			page.getByRole('link', { name: 'Test few left' }).first()
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
		await page.evaluate(() => (window.loadedOnce = true));

		await page.getByRole('checkbox', { name: /^Christmas/ }).check();

		await expect(page).toHaveURL(/categories=christmas/);
		await expect(
			page.getByRole('button', { name: 'Remove Christmas' })
		).toBeVisible();
		await expect(
			page.getByRole('link', { name: 'Test bauble' }).first()
		).toBeVisible();
		await expect(
			page.getByRole('link', { name: 'Test few left' })
		).toHaveCount(0);
		await expect(page.locator('.wp-block-matter-results-count')).toHaveText(
			'2 results'
		);
		expect(await page.evaluate(() => window.loadedOnce)).toBe(true);
		expect(await violations(page)).toEqual([]);
	});

	test('the shop filters its products, a price range included', async ({
		page,
	}) => {
		await page.goto('/shop/?min_price=20');

		await expect(
			page.getByRole('link', { name: 'Test wreath' }).first()
		).toBeVisible();
		await expect(
			page.getByRole('link', { name: 'Test bauble' })
		).toHaveCount(0);
		await expect(
			page.getByRole('button', { name: /^Remove From/ })
		).toBeVisible();

		await page.getByRole('button', { name: 'Clear all' }).first().click();
		await expect(page).not.toHaveURL(/min_price/);
		await expect(
			page.getByRole('link', { name: 'Test bauble' }).first()
		).toBeVisible();
	});

	test('on a phone, the filters open in a drawer that "Show results" closes', async ({
		page,
	}) => {
		await page.setViewportSize(MOBILE);
		await page.goto(`/${PAGE_PREFIX}filters/`);

		const toggle = page.getByRole('button', { name: /^Filters/ });
		const drawer = page.getByRole('dialog', { name: 'Filters' });

		await toggle.click();
		await expect(drawer).toBeVisible();
		expect(await violations(page)).toEqual([]);

		await drawer.getByRole('checkbox', { name: /^Christmas/ }).check();
		await expect(
			drawer.getByRole('button', { name: 'Show 2 results' })
		).toBeVisible();

		await drawer.getByRole('button', { name: 'Show 2 results' }).click();
		await expect(drawer).toBeHidden();
		await expect(toggle).toContainText('1');
	});

	test('on a phone, a product, the basket and checkout pass axe, with nothing between the bands', async ({
		page,
	}) => {
		await page.setViewportSize(MOBILE);
		await addToBasket(page, 'test-bauble');
		await page.reload();
		// The notices group is hidden while it's empty (not just empty: it
		// would still add a band's space).
		await expect(
			page.locator('.site-main > .wp-block-group', {
				has: page.locator('> .wp-block-woocommerce-store-notices'),
			})
		).toHaveCSS('display', 'none');
		// With the product in the basket, WooCommerce's button reads "1 in
		// cart" but keeps the aria-label "Add to cart: …" (WooCommerce's).
		expect(await violations(page, ['label-content-name-mismatch'])).toEqual(
			[]
		);

		await page.goto('/cart/');
		await expect(page.getByText('Test bauble').first()).toBeVisible();
		expect(await violations(page)).toEqual([]);

		await page.goto('/checkout/');
		await expect(
			page.getByRole('link', { name: /return to (basket|cart)/i })
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});

	test('an empty basket passes axe', async ({ page }) => {
		await page.goto('/cart/');

		await expect(
			page.locator('.wp-block-woocommerce-empty-cart-block')
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});

	test('a coupon applies as a chip, a wrong code shows an error, and the basket passes axe', async ({
		page,
	}) => {
		await addToBasket(page, 'test-bauble');
		await page.goto('/cart/');

		const coupons = page.locator('.wc-block-components-totals-coupon');

		await page.getByRole('button', { name: /add coupons?/i }).click();
		await coupons.getByRole('textbox').fill('not-a-coupon');
		await coupons.getByRole('button', { name: /apply/i }).click();
		await expect(
			coupons.locator('.wc-block-components-validation-error')
		).toBeVisible();

		await coupons.getByRole('textbox').fill(COUPON);
		await coupons.getByRole('button', { name: /apply/i }).click();
		await expect(page.locator('.wc-block-components-chip')).toContainText(
			COUPON
		);
		expect(await violations(page)).toEqual([]);
	});

	test('checkout shows each missing field, and an order by bank transfer is received', async ({
		page,
	}) => {
		await addToBasket(page, 'test-gift-card');
		await page.goto('/checkout/');

		const placeOrder = page.getByRole('button', { name: /place order/i });

		await placeOrder.click();
		await expect(
			page.locator('.wc-block-components-text-input.has-error').first()
		).toBeVisible();
		// WooCommerce's fields point aria-errormessage at a message whose
		// wrapper (not the message itself) is role="alert". It is announced,
		// but axe wants the live region on the referenced element, and reports
		// it as aria-valid-attr-value.
		expect(await violations(page, ['aria-valid-attr-value'])).toEqual([]);

		for (const [label, value] of [
			['Email address', 'buyer@example.com'],
			['First name', 'Test'],
			['Last name', 'Buyer'],
			['Address', '2 Order Lane'],
			['City', 'Leeds'],
			['Postcode', 'LS1 2AB'],
		]) {
			await page.getByLabel(label, { exact: true }).fill(value);
		}

		await placeOrder.click();
		await expect(page).toHaveURL(/order-received/);
		await expect(
			page.getByRole('heading', { level: 1, name: /order received/i })
		).toBeVisible();
		expect(await violations(page)).toEqual([]);
	});
});

test.describe('My Account', () => {
	/**
	 * A customer with an address and a processing order, logged in.
	 *
	 * @param {Object} page         Page.
	 * @param {Object} requestUtils WordPress request utils.
	 */
	const logInAsCustomer = async (page, requestUtils) => {
		const username = `customer${Date.now()}`;
		const email = `${username}@example.com`;
		const password = 'Customer-password-1';
		const customer = await requestUtils.rest({
			path: '/wc/v3/customers',
			method: 'POST',
			data: {
				email,
				username,
				password,
				first_name: 'Test',
				last_name: 'Customer',
				billing: {
					first_name: 'Test',
					last_name: 'Customer',
					address_1: '1 Test Street',
					city: 'Leeds',
					postcode: 'LS1 1AA',
					country: 'GB',
					email,
				},
			},
		});
		const [product] = await requestUtils.rest({
			path: '/wc/v3/products',
			params: { search: 'Test bauble' },
		});

		await requestUtils.rest({
			path: '/wc/v3/orders',
			method: 'POST',
			data: {
				customer_id: customer.id,
				status: 'processing',
				line_items: [{ product_id: product.id, quantity: 1 }],
			},
		});

		await page.goto('/my-account/');
		await page.locator('#username').fill(username);
		await page.locator('#password').fill(password);
		await page.getByRole('button', { name: /log in/i }).click();
		await expect(page.locator('.my-account-header__user')).toContainText(
			username
		);
	};

	test('the dashboard is an overview, and every section passes axe', async ({
		page,
		requestUtils,
	}) => {
		await logInAsCustomer(page, requestUtils);

		await expect(
			page.locator('.account-orders__item .account-status--processing')
		).toBeVisible();
		await expect(
			page.locator('.account-overview address').first()
		).toContainText('1 Test Street');
		expect(await violations(page)).toEqual([]);

		for (const section of ['orders', 'edit-address', 'edit-account']) {
			await page.goto(`/my-account/${section}/`);
			await expect(page.locator('.my-account-title')).toBeVisible();
			expect(await violations(page), section).toEqual([]);
		}
	});

	test('on a phone, the navigation scrolls within the page', async ({
		page,
		requestUtils,
	}) => {
		await page.setViewportSize(MOBILE);
		await logInAsCustomer(page, requestUtils);
		await page.goto('/my-account/edit-account/');

		expect(
			await page.evaluate(
				() => document.documentElement.scrollWidth <= window.innerWidth
			)
		).toBe(true);
		await expect(
			page.locator('.woocommerce-MyAccount-navigation .is-active')
		).toBeInViewport();
	});
});
