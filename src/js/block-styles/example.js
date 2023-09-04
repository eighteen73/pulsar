/**
 * This file serves as an example of how to register and unregister block styles.
 */
import domReady from '@wordpress/dom-ready';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

domReady(() => {
	registerBlockStyle('core/button', {
		name: 'huge',
		label: 'Huge',
	});

	unregisterBlockStyle('core/button', 'outline');
});
