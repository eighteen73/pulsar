const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

/**
 * Map our custom packages.
 *
 * @param {string} request Requested module
 *
 * @return {(string|undefined)} Script global
 */
function requestToHandle(request) {
	if (request === 'alpinejs') {
		return 'alpine';
	}

	if (request === '@splidejs/splide') {
		return 'splide';
	}
}

/**
 * Externalize our custom packages.
 *
 * @param {string} request Requested module
 *
 * @return {(string|undefined)} Script global
 */
function requestToExternal(request) {
	if (request === 'alpinejs') {
		return 'Alpine';
	}

	if (request === '@splidejs/splide') {
		return 'Splide';
	}
}

module.exports = {
	...defaultConfig,
	entry: {
		...getWebpackEntryPoints(),
		'app-css': ['./src/css/app.css'],
		'app-js': ['./src/js/app.js'],
		'editor-css': ['./src/css/app.css'],
		'editor-js': ['./src/js/editor.js'],
	},
	plugins: [
		...defaultConfig.plugins.filter(
			(plugin) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
		),
		new DependencyExtractionWebpackPlugin({
			requestToExternal,
			requestToHandle,
		}),
	],
};
