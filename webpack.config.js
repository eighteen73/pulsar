const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const MergeJsonWebpackPlugin = require('merge-jsons-webpack-plugin');
const { sync: glob } = require('fast-glob');
const { basename, resolve } = require('path');

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

/**
 * Converts a legacy path to the entry pair supported by webpack, e.g.:
 * `./entry-one.js` -> `[ 'entry-one', './entry-one.js] ]`
 * `entry-two.js` -> `[ 'entry-two', './entry-two.js' ]`
 *
 * @param {string} path The path provided.
 *
 * @return {string[]} The entry pair of its name and the file path.
 */
const stylesheetPathToEntry = (path) => {
	const entryName = basename(path, '.scss');

	if (!path.startsWith('./')) {
		path = './' + path;
	}

	return [entryName, path];
};

function getBlockStylesEntryPoints() {
	const styles = glob('./src/css/blocks/*.scss');

	const entry = {};
	styles.forEach((fileArg) => {
		const [entryName, path] = fileArg.includes('=')
			? fileArg.split('=')
			: stylesheetPathToEntry(fileArg);
		entry['css/blocks/' + entryName] = path;
	});
	process.env.WP_ENTRY = JSON.stringify(entry);

	return entry;
}

module.exports = {
	...defaultConfig,
	stats: 'minimal',
	entry: {
		...getWebpackEntryPoints(),
		...getBlockStylesEntryPoints(),
		'css/app-styles': ['./src/css/app.scss'],
		'css/editor-styles': ['./src/css/editor.scss'],
		'js/app-scripts': ['./src/js/app.js'],
		'js/editor-scripts': ['./src/js/editor.js'],
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
	plugins: [
		...defaultConfig.plugins,
		new RemoveEmptyScriptsPlugin({
			stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
		}),
		new MergeJsonWebpackPlugin({
			space: '\t',
			files: [
				'./config/theme-json/base.json',
				'./config/theme-json/settings.json',
				'./config/theme-json/styles.json',
				'./config/theme-json/customTemplates.json',
				'./config/theme-json/templateParts.json',
			],
			output: {
				fileName: '../theme.json',
			},
		}),
	],
};
