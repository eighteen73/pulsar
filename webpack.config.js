const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

module.exports = {
	...defaultConfig,
	stats: 'minimal',
	entry: {
		...getWebpackEntryPoints(),
		'app-styles': ['./src/css/app.css'],
		'app-scripts': ['./src/js/app.js'],
		'editor-styles': ['./src/css/editor.css'],
		'editor-scripts': ['./src/js/editor.js'],
	},
	output: {
		path: __dirname + '/dist',
		publicPath: '/dist',
	},
	devServer: {
		...defaultConfig.devServer,
		hot: true,
		static: __dirname + '/dist/',
		allowedHosts: 'all',
		host: 'localhost',
		port: 1873,
		proxy: {
			'/dist': {
				pathRewrite: {
					'^/dist': '',
				},
			},
		},
	},
};
