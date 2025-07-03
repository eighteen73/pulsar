const wordpressConfig = require('@wordpress/scripts/config/webpack.config');
const { getProjectSourcePath } = require('@wordpress/scripts/utils');
const { mergeWithRules } = require('webpack-merge');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CopyWebpackPlugin = require('copy-webpack-plugin'); // eslint-disable-line -- part of @wordpress/scripts
const MergeJsonWebpackPlugin = require('merge-jsons-webpack-plugin');
const { sync: glob } = require('fast-glob');
const { basename } = require('path');

const isProduction = process.env.NODE_ENV === 'production';

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
					{
						loader: require.resolve('resolve-url-loader'),
						options: {
							root: __dirname,
						},
					},
				],
			},
			{
				test: /\.(sc|sa)ss$/,
				use: [
					{
						loader: require.resolve('css-loader'),
						options: {
							importLoaders: 1,
							sourceMap: !isProduction,
							modules: {
								auto: true,
							},
							url: false,
						},
					},
					{
						loader: require.resolve('resolve-url-loader'),
						options: {
							root: __dirname,
						},
					},
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
		...wordpressConfig.entry(),
		...getBlockStylesEntryPoints(),
		'css/app': ['./src/css/app.scss'],
		'css/editor': ['./src/css/editor.scss'],
		'css/woocommerce-account': ['./src/css/woocommerce-account.scss'],
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

// Instead of trying to modify the WordPress config after merging,
// we'll directly modify the WordPress config before merging
const modifiedWordpressConfig = { ...wordpressConfig };

// Find the SCSS rule in the WordPress config
const scssRuleIndex = modifiedWordpressConfig.module.rules.findIndex(
	(rule) => rule.test && rule.test.toString().includes('sc|sa)ss')
);

if (scssRuleIndex !== -1) {
	const scssRule = modifiedWordpressConfig.module.rules[scssRuleIndex];

	// Find the sass-loader in the array
	const sassLoaderIndex = scssRule.use.findIndex(
		(loader) =>
			typeof loader === 'object' &&
			loader.loader &&
			loader.loader.includes('sass-loader')
	);

	if (sassLoaderIndex !== -1) {
		// Check if resolve-url-loader is already in the array
		const hasResolveUrlLoader = scssRule.use.some(
			(loader) =>
				typeof loader === 'object' &&
				loader.loader &&
				loader.loader.includes('resolve-url-loader')
		);

		// If resolve-url-loader is not already present, insert it before sass-loader
		if (!hasResolveUrlLoader) {
			scssRule.use.splice(sassLoaderIndex, 0, {
				loader: require.resolve('resolve-url-loader'),
				options: {
					root: __dirname,
				},
			});
		}
	}
}

// Now merge with our custom config
const config = mergeWithRules({
	module: {
		rules: {
			test: 'match',
			use: {
				loader: 'match',
				options: 'replace',
			},
		},
	},
})(modifiedWordpressConfig, pulsarConfig);

module.exports = config;
