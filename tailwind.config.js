const defaultTheme = require('tailwindcss/defaultTheme');

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
		extend: {},
	},
	plugins: [
		require('@tailwindcss/typography'),
		require('@tailwindcss/forms'),
	],
};
