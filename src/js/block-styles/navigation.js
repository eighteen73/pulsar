/**
 * This file serves as an example of how to register and unregister block styles.
 */
import domReady from '@wordpress/dom-ready';
import { registerBlockStyle } from '@wordpress/blocks';

domReady(() => {
	registerBlockStyle('core/navigation', {
		name: 'pulsar-overlay',
		label: 'Overlay',
	});
});
