/**
 * This file serves as an example of how to register and unregister block styles.
 */
import domReady from '@wordpress/dom-ready';
import { unregisterBlockStyle } from '@wordpress/blocks';

domReady(() => {
	unregisterBlockStyle('core/quote', 'plain');
});
