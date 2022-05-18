const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,
		'app': [ './src/css/app.css', './src/js/app.js' ],
		'editor': [ './src/css/editor.css', './src/js/editor.js' ],
	}
};
