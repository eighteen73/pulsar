const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
	content: require('fast-glob').sync(['./**/*.php', './src/**/*.{css,js}']),
	theme: {
		container: {
			center: true,
		},
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
