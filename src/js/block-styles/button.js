/**
 * This file serves as an example of how to register and unregister block styles.
 */
import domReady from '@wordpress/dom-ready';
import { unregisterBlockStyle, registerBlockStyle } from '@wordpress/blocks';

domReady(() => {
	const blocks = [
		'core/button',
		'woocommerce/mini-cart-checkout-button-block',
		'woocommerce/mini-cart-cart-button-block',
		'woocommerce/mini-cart-shopping-button-block',
	];

	blocks.forEach((block) => {
		unregisterBlockStyle(block, 'fill');
		unregisterBlockStyle(block, 'outline');
	});

	blocks.forEach((block) => {
		registerBlockStyle(block, {
			name: 'primary',
			label: 'Primary',
			isDefault: true,
		});

		registerBlockStyle(block, {
			name: 'secondary',
			label: 'Secondary',
			isDefault: false,
		});

		registerBlockStyle(block, {
			name: 'link',
			label: 'Link',
			isDefault: false,
		});
	});
});
