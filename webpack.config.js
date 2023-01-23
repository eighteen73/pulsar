const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

function regexEqual(x, y) {
	return (
		x instanceof RegExp &&
		y instanceof RegExp &&
		x.source === y.source &&
		x.global === y.global &&
		x.ignoreCase === y.ignoreCase &&
		x.multiline === y.multiline
	);
}

// We need to disable css-loader URL tampering in the Gutenberg webpack config so we can write relative URLs.
// @see https://github.com/WordPress/gutenberg/blob/ba01e5d7851d6211d020687b5ae927f47ea4683e/packages/scripts/config/webpack.config.js#L51
defaultConfig.module.rules = defaultConfig.module.rules.map((item) => {
	if (
		regexEqual(item.test, /\.css$/) ||
		regexEqual(item.test, /\.(sc|sa)ss$/)
	) {
		item.use = item.use.map((useRule) => {
			if (useRule.loader.match(/\/css-loader\//)) {
				useRule.options.url = false;
			}
			return useRule;
		});
	}

	return item;
});

module.exports = {
	...defaultConfig,
	stats: 'minimal',
	entry: {
		...getWebpackEntryPoints(),
		'app-styles': ['./src/css/app.scss'],
		'app-scripts': ['./src/js/app.js'],
		'editor-styles': ['./src/css/editor.scss'],
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
