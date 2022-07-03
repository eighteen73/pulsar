import Alpine from 'alpinejs';
import { domReady } from '@wordpress/dom-ready';
import './components/menu';

/**
 * app.main
 */
const main = async (err) => {
	if (err) {
		// handle hmr errors
		console.error(err);
	}

	// application code
	window.Alpine = Alpine;
	Alpine.start();
};

/**
 * Initialize
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
domReady(main);
import.meta.webpackHot?.accept(main);
