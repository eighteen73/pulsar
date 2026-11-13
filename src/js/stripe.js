/**
 * Stripe's card fields and express payment buttons in the theme's style.
 *
 * Stripe's fields are in its own iframe, so the theme's CSS can't reach them:
 * they're styled by an "appearance" (https://docs.stripe.com/elements/appearance-api)
 * that WooCommerce Stripe reads from `wc_stripe_upe_params` (`blocksAppearance`
 * on the block checkout, `appearance` on the classic checkout, the pay for
 * order page and My Account's payment methods), or otherwise guesses from the
 * page. Here they're defined as the theme's: its field styles (the
 * `field-input` mixin), from the colour scheme around the payment form,
 * resolved when Stripe asks for them. A site's own appearance (set with the
 * `wc_stripe_upe_params` filter) is left alone.
 *
 * The font is the theme's only if Stripe's iframe can load it: from Google
 * Fonts, or with CORS headers on the font files and their stylesheet.
 */
const params = window.wc_stripe_upe_params;

/**
 * Where the payment form is, for its colour scheme.
 *
 * @return {HTMLElement} Element.
 */
const host = () =>
	document.querySelector(
		'.wc-block-checkout__payment-method, #payment, #add_payment_method'
	) ?? document.body;

/**
 * Resolve CSS values (custom properties, calc()) to what Stripe accepts:
 * computed colours (rgb()) and lengths (px).
 *
 * @param {Object} styles Property: CSS value.
 * @return {Object} Property: computed value.
 */
const resolve = (styles) => {
	const probe = document.createElement('span');

	probe.setAttribute('aria-hidden', 'true');
	Object.assign(probe.style, styles, {
		position: 'absolute',
		visibility: 'hidden',
		pointerEvents: 'none',
	});
	host().append(probe);

	const computed = window.getComputedStyle(probe);
	const values = Object.fromEntries(
		Object.keys(styles).map((property) => [property, computed[property]])
	);

	probe.remove();

	return values;
};

/**
 * The theme's appearance for Stripe's fields.
 *
 * @param {boolean} floating Whether the labels float in the fields (the
 *                           block checkout's, as its own fields do).
 * @return {Object} Stripe appearance.
 */
const appearance = (floating) => {
	// Colours (as text colours, to resolve to rgb()), then the field's sizes.
	const colour = (token) => resolve({ color: token }).color;
	const text = colour('var(--scheme--text)');
	const subtle = colour('var(--scheme--text-subtle)');
	const surface = colour('var(--scheme--background-surface)');
	const border = colour('var(--scheme--border-strong)');
	const quietBorder = colour('var(--scheme--border)');
	const highlight = colour('var(--scheme--highlight)');
	const error = colour('var(--wp--preset--color--status-error)');
	const fill = colour('var(--scheme--background-fill)');
	const field = resolve({
		borderRadius: 'var(--wp--preset--border-radius--sm)',
		fontSize: 'var(--wp--preset--font-size--md)',
		paddingTop: 'var(--wp--preset--spacing--md)',
		paddingLeft: 'var(--wp--preset--spacing--lg)',
	});
	const body = window.getComputedStyle(document.body);
	const labelWeight = body
		.getPropertyValue('--wp--custom--typography--font-weight--label')
		.trim();

	return {
		theme: 'stripe',
		labels: floating ? 'floating' : 'above',
		variables: {
			colorPrimary: fill,
			colorBackground: surface,
			colorText: text,
			colorTextSecondary: subtle,
			colorTextPlaceholder: subtle,
			colorDanger: error,
			fontFamily: body.fontFamily,
			fontSizeBase: field.fontSize,
			borderRadius: field.borderRadius,
		},
		rules: {
			'.Input': {
				backgroundColor: surface,
				border: `1px solid ${border}`,
				boxShadow: 'none',
				color: text,
				padding: `${field.paddingTop} ${field.paddingLeft}`,
			},
			'.Input:focus': {
				borderColor: highlight,
				boxShadow: `0 0 0 1px ${highlight}`,
				outline: 'none',
			},
			'.Input--invalid': {
				borderColor: error,
				boxShadow: `0 0 0 1px ${error}`,
				color: text,
			},
			'.Label': {
				color: text,
				...(floating ? {} : { fontWeight: labelWeight }),
			},
			'.Error': { color: error },
			// Each payment method (card, Link, …): an outline, as the form
			// already sits on the chosen payment option's tint.
			'.AccordionItem': {
				backgroundColor: 'transparent',
				border: `1px solid ${quietBorder}`,
				boxShadow: 'none',
			},
		},
	};
};

if (params) {
	[
		['blocksAppearance', true],
		['appearance', false],
	].forEach(([key, floating]) => {
		if (params[key]) {
			return;
		}

		let value;

		Object.defineProperty(params, key, {
			configurable: true,
			enumerable: true,
			get: () => (value ??= appearance(floating)),
		});
	});
}

/**
 * The theme's buttons' corner radius, in pixels.
 *
 * @return {string} Radius.
 */
const buttonRadius = () => {
	const probe = document.createElement('button');

	probe.className = 'wp-element-button';
	probe.setAttribute('aria-hidden', 'true');
	probe.tabIndex = -1;
	Object.assign(probe.style, { position: 'absolute', visibility: 'hidden' });
	host().append(probe);

	const radius = parseFloat(
		window.getComputedStyle(probe).borderTopLeftRadius
	);

	probe.remove();

	return String(Number.isNaN(radius) ? 4 : radius);
};

// Express payment buttons (Google Pay, Apple Pay, Link…), rounded as the
// theme's buttons: Stripe's radius follows its button size setting. Its
// settings are in `wc_stripe_express_checkout_params` and, on the block cart
// and checkout, WooCommerce's `stripe_data` setting.
[
	window.wc_stripe_express_checkout_params?.button,
	window.wc?.wcSettings?.getSetting?.('stripe_data')?.button,
]
	.filter(Boolean)
	.forEach((button) => {
		let value;

		Object.defineProperty(button, 'radius', {
			configurable: true,
			enumerable: true,
			get: () => (value ??= buttonRadius()),
		});
	});
