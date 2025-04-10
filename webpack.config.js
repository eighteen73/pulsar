const wordpressConfig = require('@wordpress/scripts/config/webpack.config');
const { getProjectSourcePath } = require('@wordpress/scripts/utils');
const { mergeWithRules } = require('webpack-merge');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CopyWebpackPlugin = require('copy-webpack-plugin'); // eslint-disable-line
const MergeJsonWebpackPlugin = require('merge-jsons-webpack-plugin');
const { sync: glob } = require('fast-glob');
const { basename } = require('path');

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

/**
 * Get all of the block specific stylesheets to use for entry points.
 *
 * @return {Object} An object of entry keys and values.
 */
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

const defaultEntryPoints = wordpressConfig.entry();

/**
 * Pulsars default config.
 */
const pulsarConfig = {
	module: {
		rules: [
			{
				test: /\.css$/,
				use: [
					{
						loader: require.resolve('css-loader'),
						options: {
							url: false,
						},
					},
					// {
					// 	loader: require.resolve('resolve-url-loader'),
					// 	options: {
					// 		root: __dirname,
					// 	},
					// },
				],
			},
			{
				test: /\.(sc|sa)ss$/,
				use: [
					{
						loader: require.resolve('css-loader'),
						options: {
							url: false,
						},
					},
					// {
					// 	loader: require.resolve('resolve-url-loader'),
					// 	options: {
					// 		root: __dirname,
					// 	},
					// },
					{
						loader: require.resolve('sass-loader'),
						options: {
							sourceMap: true, // Required for resolve-url-loader
							sassOptions: {
								loadPaths: [__dirname + '/src/css'],
							},
						},
					},
				],
			},
			{
				test: /\.svg$/,
				issuer: /\.(j|t)sx?$/,
				use: ['@svgr/webpack'],
				type: 'javascript/auto',
			},
		],
	},
	stats: 'minimal',
	entry: {
		...defaultEntryPoints,
		...getBlockStylesEntryPoints(),
		'css/app': ['./src/css/app.scss'],
		'css/editor': ['./src/css/editor.scss'],
		'js/app': ['./src/js/app.js'],
		'js/editor': ['./src/js/editor.js'],
	},
	devServer: {
		hot: true,
		static: __dirname + '/build/',
		allowedHosts: 'all',
		host: 'localhost',
		port: 1873,
		proxy: {
			'/build': {
				pathRewrite: {
					'^/build': '',
				},
			},
		},
	},
	plugins: [
		new CopyWebpackPlugin({
			patterns: [
				{
					from: '**/*.{svg,jpg,png}',
					context: getProjectSourcePath(),
					noErrorOnMissing: true,
				},
			],
		}),
		new RemoveEmptyScriptsPlugin({
			stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
		}),
		new MergeJsonWebpackPlugin({
			space: '\t',
			files: [
				'./config/theme-json/base.json',
				'./config/theme-json/settings.general.json',
				'./config/theme-json/settings.background.json',
				'./config/theme-json/settings.blocks.json',
				'./config/theme-json/settings.border.json',
				'./config/theme-json/settings.color.json',
				'./config/theme-json/settings.custom.json',
				'./config/theme-json/settings.dimensions.json',
				'./config/theme-json/settings.layout.json',
				'./config/theme-json/settings.lightbox.json',
				'./config/theme-json/settings.position.json',
				'./config/theme-json/settings.shadow.json',
				'./config/theme-json/settings.spacing.json',
				'./config/theme-json/settings.typography.json',
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

module.exports = mergeWithRules({
	module: {
		rules: {
			test: 'match',
			use: {
				loader: 'match',
				options: 'replace',
			},
		},
	},
})(wordpressConfig, pulsarConfig);
