/**
 * The content the browser tests use, created through the REST API before
 * they run (global-setup.js). Only the tests' own content is replaced, so
 * WooCommerce's shop, cart and checkout pages stay.
 *
 * Adapt it per project: add the project's sections to SECTIONS, its menus
 * and its settings.
 */

/** The colour schemes in config/theme-json/settings.custom.json. */
const SCHEMES = ['default', 'subtle', 'brand', 'contrast'];

const PAGE_PREFIX = 'pulsar-test-';

// A 1×1 image, so tests don't depend on the network.
const IMAGE =
	'data:image/gif;base64,R0lGODlhAQABAIAAAMLCwgAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==';

const slot = (name, inner) =>
	`<!-- wp:matter/slot {"name":"${name}"} -->${inner}<!-- /wp:matter/slot -->`;
const heading = (level, text) =>
	`<!-- wp:heading {"level":${level}} --><h${level} class="wp-block-heading">${text}</h${level}><!-- /wp:heading -->`;
const paragraph = (text) =>
	`<!-- wp:paragraph --><p>${text}</p><!-- /wp:paragraph -->`;
const image = (alt) =>
	`<!-- wp:image --><figure class="wp-block-image"><img src="${IMAGE}" alt="${alt}"/></figure><!-- /wp:image -->`;
const buttons = (text) =>
	`<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/">${text}</a></div><!-- /wp:button --></div><!-- /wp:buttons -->`;
const section = (name, attributes, slots) =>
	`<!-- wp:pulsar/${name} ${JSON.stringify(attributes)} -->${slots}<!-- /wp:pulsar/${name} -->`;

/**
 * A page with every Pulsar section, in one colour scheme.
 *
 * @param {string} scheme Scheme slug.
 * @return {string} Block markup.
 */
const sectionsPage = (scheme) => {
	const attrs = { scheme };
	const card = (n) =>
		section(
			'card',
			{ ...attrs, link: { url: '/' } },
			slot('media', image(`Card ${n} image`)) +
				slot(
					'content',
					heading(3, `Card ${n}`) + paragraph(`About card ${n}.`)
				)
		);

	return [
		section(
			'hero',
			attrs,
			slot(
				'content',
				heading(1, `Sections in the ${scheme} scheme`) +
					paragraph('An introduction to the page.') +
					buttons('Get started')
			) + slot('media', image('Hero image'))
		),
		section(
			'text',
			attrs,
			slot(
				'content',
				heading(2, 'Text') + paragraph('A paragraph of body text.')
			)
		),
		section(
			'media-text',
			attrs,
			slot('media', image('Media image')) +
				slot(
					'content',
					heading(2, 'Media and text') +
						paragraph('Beside the image.')
				)
		),
		section(
			'cards',
			attrs,
			slot('intro', heading(2, 'Cards')) +
				slot('items', [1, 2, 3].map(card).join(''))
		),
		section(
			'cta',
			attrs,
			slot(
				'content',
				heading(2, 'Call to action') +
					paragraph('Do the thing.') +
					buttons('Contact us')
			)
		),
	].join('\n');
};

/** Site settings (Appearance › Options), as config/settings.php declares. */
const SETTINGS = {
	contact: {
		phone: '0113 496 0000',
		email: 'hello@example.com',
		address: '1 Test Street\nLeeds',
	},
	header: { cta_label: 'Book a call', cta_url: 'https://example.com/book' },
	footer: { copyright: '{year} {site}' },
};

/**
 * Delete what an earlier run made.
 *
 * @param {Object} requestUtils WordPress request utils.
 */
async function clean(requestUtils) {
	const pages = await requestUtils.rest({
		path: '/wp/v2/pages',
		params: { per_page: 100, status: 'publish,draft', context: 'edit' },
	});

	await Promise.all(
		pages
			.filter(({ slug }) => slug.startsWith(PAGE_PREFIX))
			.map(({ id }) =>
				requestUtils.rest({
					path: `/wp/v2/pages/${id}`,
					method: 'DELETE',
					params: { force: true },
				})
			)
	);

	await requestUtils.deleteAllPosts();
	await requestUtils.deleteAllMenus?.();

	// (Except the extension tests' products, which their orders, subscription
	// and booking keep: tests/wp-env/seed-extensions.php.)
	const products = await requestUtils.rest({
		path: '/wc/v3/products',
		params: { per_page: 100 },
	});

	await Promise.all(
		products
			.filter(({ slug }) => !slug.startsWith('pulsar-ext-'))
			.map(({ id }) =>
				requestUtils.rest({
					path: `/wc/v3/products/${id}`,
					method: 'DELETE',
					params: { force: true },
				})
			)
	);

	const coupons = await requestUtils.rest({
		path: '/wc/v3/coupons',
		params: { per_page: 100 },
	});

	await Promise.all(
		coupons.map(({ id }) =>
			requestUtils.rest({
				path: `/wc/v3/coupons/${id}`,
				method: 'DELETE',
				params: { force: true },
			})
		)
	);
}

/**
 * A term, created or found.
 *
 * @param {Object} requestUtils WordPress request utils.
 * @param {string} path         REST collection path.
 * @param {string} name         Term name.
 * @return {Promise<number>} Term ID.
 */
async function term(requestUtils, path, name) {
	try {
		return (
			await requestUtils.rest({ path, method: 'POST', data: { name } })
		).id;
	} catch (error) {
		// Already there (term_exists gives its ID).
		const id = error?.data?.resource_id ?? error?.data?.term_id;

		if (id) {
			return id;
		}

		throw error;
	}
}

/**
 * Menus in each of Pulsar's locations.
 *
 * @param {Object} requestUtils WordPress request utils.
 */
async function createMenus(requestUtils) {
	const menu = async (name, location, items) => {
		const { id } = await requestUtils.rest({
			path: '/wp/v2/menus',
			method: 'POST',
			data: { name, locations: [location] },
		});

		for (const { children = [], ...item } of items) {
			const parent = await requestUtils.rest({
				path: '/wp/v2/menu-items',
				method: 'POST',
				data: { status: 'publish', menus: id, ...item },
			});

			for (const child of children) {
				await requestUtils.rest({
					path: '/wp/v2/menu-items',
					method: 'POST',
					data: {
						status: 'publish',
						menus: id,
						parent: parent.id,
						...child,
					},
				});
			}
		}
	};

	await menu('Primary', 'primary', [
		{
			title: 'Services',
			url: '/services/',
			children: [
				{ title: 'Design', url: '/services/design/' },
				{ title: 'Build', url: '/services/build/' },
			],
		},
		{ title: 'Blog', url: '/' },
		{ title: 'Shop', url: '/shop/' },
	]);
	await menu('Footer 1', 'footer-1', [{ title: 'About', url: '/about/' }]);
	await menu('Footer 2', 'footer-2', [
		{ title: 'Careers', url: '/careers/' },
	]);
	await menu('Legal', 'legal', [
		{ title: 'Privacy policy', url: '/privacy-policy/' },
	]);
}

/** The coupon the basket test applies. */
const COUPON = 'pulsar10';

/**
 * Products with their filters beside them: WooCommerce's product filters
 * (active filters, price, rating, category, availability) inside a Product
 * Collection, whose query they filter.
 *
 * @return {string} Block markup.
 */
const filtersPage = () => {
	const filter = (block, title, inner, attributes = {}) =>
		`<!-- wp:woocommerce/${block} ${JSON.stringify(attributes)} --><div class="wp-block-woocommerce-${block}">${heading(3, title)}${inner}</div><!-- /wp:woocommerce/${block} -->`;
	const checkboxes =
		'<!-- wp:woocommerce/product-filter-checkbox-list --><div class="wp-block-woocommerce-product-filter-checkbox-list wc-block-product-filter-checkbox-list"></div><!-- /wp:woocommerce/product-filter-checkbox-list -->';
	const query = {
		perPage: 9,
		pages: 0,
		offset: 0,
		postType: 'product',
		order: 'asc',
		orderBy: 'title',
		inherit: false,
		filterable: true,
		isProductCollectionBlock: true,
		woocommerceStockStatus: ['instock', 'outofstock', 'onbackorder'],
	};

	return `<!-- wp:woocommerce/product-collection {"queryId":9,"query":${JSON.stringify(query)},"tagName":"div","displayLayout":{"type":"flex","columns":3,"shrinkColumns":true},"queryContextIncludes":["collection"]} -->
<div class="wp-block-woocommerce-product-collection"><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"width":"30%"} --><div class="wp-block-column" style="flex-basis:30%"><!-- wp:woocommerce/product-filters --><div class="wp-block-woocommerce-product-filters wc-block-product-filters">${heading(2, 'Filters')}<!-- wp:woocommerce/product-filter-active --><div class="wp-block-woocommerce-product-filter-active"><!-- wp:woocommerce/product-filter-removable-chips --><div class="wp-block-woocommerce-product-filter-removable-chips wc-block-product-filter-removable-chips"></div><!-- /wp:woocommerce/product-filter-removable-chips --></div><!-- /wp:woocommerce/product-filter-active -->${filter('product-filter-price', 'Price', '<!-- wp:woocommerce/product-filter-price-slider {"showInputFields":true} --><div class="wp-block-woocommerce-product-filter-price-slider wc-block-product-filter-price-slider"></div><!-- /wp:woocommerce/product-filter-price-slider -->')}${filter('product-filter-rating', 'Rating', checkboxes)}${filter('product-filter-taxonomy', 'Category', checkboxes, { showCounts: true })}${filter('product-filter-status', 'Availability', checkboxes)}</div><!-- /wp:woocommerce/product-filters --></div><!-- /wp:column --><!-- wp:column {"width":"70%"} --><div class="wp-block-column" style="flex-basis:70%"><!-- wp:woocommerce/product-template --><!-- wp:matter/loop-item /--><!-- /wp:woocommerce/product-template --></div><!-- /wp:column --></div><!-- /wp:columns --></div>
<!-- /wp:woocommerce/product-collection -->`;
};

/**
 * Create everything the tests use.
 *
 * @param {Object} requestUtils WordPress request utils.
 */
async function createContent(requestUtils) {
	await clean(requestUtils);

	for (const scheme of SCHEMES) {
		await requestUtils.createPage({
			title: `Sections: ${scheme}`,
			slug: `${PAGE_PREFIX}sections-${scheme}`,
			status: 'publish',
			content: sectionsPage(scheme),
		});
	}

	const news = await term(requestUtils, '/wp/v2/categories', 'News');

	for (const n of [1, 2, 3]) {
		await requestUtils.createPost({
			title: `News story ${n}`,
			excerpt: `The short version of story ${n}.`,
			content: paragraph(`The whole of story ${n}.`),
			status: 'publish',
			categories: [news],
		});
	}

	const christmas = await term(
		requestUtils,
		'/wc/v3/products/categories',
		'Christmas'
	);

	for (const [name, price] of [
		['Test bauble', '4.99'],
		['Test wreath', '24.00'],
	]) {
		await requestUtils.rest({
			path: '/wc/v3/products',
			method: 'POST',
			data: {
				name,
				regular_price: price,
				status: 'publish',
				categories: [{ id: christmas }],
			},
		});
	}

	// A product in each stock state, and a virtual one (checkout without
	// shipping).
	const states = await term(
		requestUtils,
		'/wc/v3/products/categories',
		'States'
	);

	for (const product of [
		{ name: 'Test out of stock', stock_status: 'outofstock' },
		{
			name: 'Test back-order',
			manage_stock: true,
			stock_quantity: 0,
			backorders: 'notify',
		},
		{ name: 'Test few left', manage_stock: true, stock_quantity: 3 },
		{ name: 'Test gift card', virtual: true },
	]) {
		await requestUtils.rest({
			path: '/wc/v3/products',
			method: 'POST',
			data: {
				regular_price: '9.00',
				status: 'publish',
				categories: [{ id: states }],
				...product,
			},
		});
	}

	// A review, so there are ratings.
	const [bauble] = await requestUtils.rest({
		path: '/wc/v3/products',
		params: { search: 'Test bauble' },
	});

	await requestUtils.rest({
		path: '/wc/v3/products/reviews',
		method: 'POST',
		data: {
			product_id: bauble.id,
			review: 'Lovely and sparkly.',
			reviewer: 'Test reviewer',
			reviewer_email: 'reviewer@example.com',
			rating: 4,
			status: 'approved',
		},
	});

	await requestUtils.rest({
		path: '/wc/v3/coupons',
		method: 'POST',
		data: { code: COUPON, discount_type: 'percent', amount: '10' },
	});

	// Orders can be placed (by bank transfer), from the UK (no state field).
	await requestUtils.rest({
		path: '/wc/v3/payment_gateways/bacs',
		method: 'PUT',
		data: { enabled: true },
	});
	await requestUtils.rest({
		path: '/wc/v3/settings/general/woocommerce_default_country',
		method: 'PUT',
		data: { value: 'GB' },
	});

	await requestUtils.createPage({
		title: 'Filters',
		slug: `${PAGE_PREFIX}filters`,
		status: 'publish',
		content: filtersPage(),
	});

	await createMenus(requestUtils);

	await requestUtils.rest({
		path: '/matter/v1/settings',
		method: 'POST',
		data: { values: SETTINGS },
	});
}

module.exports = { createContent, SCHEMES, SETTINGS, PAGE_PREFIX, COUPON };
