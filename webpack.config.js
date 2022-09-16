const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

module.exports = {
	...defaultConfig,
	entry: {
		...getWebpackEntryPoints(),
		'app-styles': ['./src/css/app.css'],
		'app-scripts': ['./src/js/app.js'],
		'editor-styles': ['./src/css/app.css'],
		'editor-scripts': ['./src/js/editor.js'],
	},
};
