const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );

module.exports = {
	...defaultConfig,
	entry: {
		...getWebpackEntryPoints(),
		'app': [ './src/css/app.css', './src/js/app.js' ],
		'editor': [ './src/css/editor.css', './src/js/editor.js' ],
	}
};
