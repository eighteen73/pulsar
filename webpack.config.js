const [
	scriptConfig,
	moduleConfig,
] = require('@wordpress/scripts/config/webpack.config');
const { getProjectSourcePath } = require('@wordpress/scripts/utils');
const { mergeWithRules } = require('webpack-merge');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CopyWebpackPlugin = require('copy-webpack-plugin'); // eslint-disable-line -- part of @wordpress/scripts
const MergeJsonWebpackPlugin = require('merge-jsons-webpack-plugin');
const { sync: glob } = require('fast-glob');
const breakpoints = require('./config/breakpoints.json');

const isProduction = process.env.NODE_ENV === 'production';

/**
 * Sass importer exposing `config/breakpoints.json` as `@use "pulsar:breakpoints"`.
 *
 * Keeps one breakpoint source for SCSS, PHP and JavaScript.
 */
const breakpointsImporter = {
	canonicalize(url) {
		return url === 'pulsar:breakpoints' ? new URL(url) : null;
	},
	load() {
		const entries = Object.entries(breakpoints)
			.map(([name, size]) => `"${name}": ${size}`)
			.join(', ');

		return { contents: `$breakpoints: (${entries});`, syntax: 'scss' };
	},
};

const entries = {
	'css/app': ['./src/css/app.scss'],
	'css/editor': ['./src/css/editor.scss'],
	'css/woocommerce-account': ['./src/css/woocommerce-account.scss'],
	'js/editor': ['./src/js/editor.js'],
};

const moduleEntries = {
	'js/app': ['./src/js/app.js'],
};
/**
 * The per-block stylesheets: `src/css/blocks/{namespace}/{block}.scss`, built
 * to `css/blocks/{namespace}/{block}.css` and loaded with that block
 * (Enqueue::block_styles()).
 *
 * @return {Object} Entry names and paths.
 */
function getBlockStylesEntryPoints() {
	return Object.fromEntries(
		glob('./src/css/blocks/*/*.scss').map((path) => [
			path.replace(/^\.\/src\//, '').replace(/\.scss$/, ''),
			[path],
		])
	);
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
								importers: [breakpointsImporter],
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
		...scriptConfig.entry(),
		...getBlockStylesEntryPoints(),
		...entries,
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
				'./config/theme-json/settings.blockVisibility.json',
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
const modifiedScriptConfig = { ...scriptConfig };

// Find the SCSS rule in the WordPress config
const scssRuleIndex = modifiedScriptConfig.module.rules.findIndex(
	(rule) => rule.test && rule.test.toString().includes('sc|sa)ss')
);

if (scssRuleIndex !== -1) {
	const scssRule = modifiedScriptConfig.module.rules[scssRuleIndex];

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
const mergedScriptConfig = mergeWithRules({
	module: {
		rules: {
			test: 'match',
			use: {
				loader: 'match',
				options: 'replace',
			},
		},
	},
})(modifiedScriptConfig, pulsarConfig);

const mergedModuleConfig = {
	...moduleConfig,
	entry: {
		...moduleConfig.entry(),
		...moduleEntries,
	},
};

module.exports = [mergedScriptConfig, mergedModuleConfig];
