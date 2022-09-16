const defaultTheme = require('tailwindcss/defaultTheme');
const { themeJson } = require('@eighteen73/tailwindcss-wordpress');
const json = require('./theme.json');

module.exports = {
	content: require('fast-glob').sync([
		'!./dist',
		'./**/*.php',
		'./src/css/**/*.css',
		'./src/js/*.js',
	]),
	theme: {
		container: ({ theme }) => ({
			center: true,
			padding: theme('spacing.6'),
		}),
		screens: {
			xs: '475px',
			...defaultTheme.screens,
		},
		colors: {
			...{
				current: 'currentColor',
				transparent: 'transparent',
			},
			...themeJson('settings.color.palette', json),
		},
		fontFamily: themeJson('settings.typography.fontFamilies', json),
		fontSize: themeJson('settings.typography.fontSizes', json),
		extend: {},
	},
	plugins: [
		require('@tailwindcss/typography'),
		require('@tailwindcss/forms'),
	],
};
