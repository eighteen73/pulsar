import domReady from '@wordpress/dom-ready';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

/**
 * editor.main
 */
const main = (err) => {
	if (err) {
		// handle hmr errors
		console.error(err);
	}

	unregisterBlockStyle('core/button', 'outline');
};

/**
 * Initialize
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
domReady(main);
import.meta.webpackHot?.accept(main);
