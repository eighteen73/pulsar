/**
 * `:is()`, `:dir()` and logical properties stay as written (every supported
 * browser has them): rewritten, `:is()` lists would change specificity, and
 * logical properties would stop following the content's direction.
 */
module.exports = {
	plugins: {
		'postcss-preset-env': {
			features: {
				'dir-pseudo-class': false,
				'is-pseudo-class': false,
				'logical-properties-and-values': false,
			},
		},
	},
};
