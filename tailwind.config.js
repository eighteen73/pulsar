const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: require('fast-glob').sync([
		'./**/*.php',
		'./resources/css/**/*.css',
		'./resources/js/*.js',
	]),
  theme: {
		container: ({ theme }) => ({
      center: true,
			padding: theme('spacing.6'),
    }),
		screens: {
      'xs': '475px',
      ...defaultTheme.screens,
    },
    extend: {},
  },
  plugins: [],
}
