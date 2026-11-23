/**
 * Matter's enhanced gate (npm run lint:gate): `matter-enhanced()` in
 * src/css/tools/mixins/_matter.scss is a copy of Matter's `enhanced()`
 * (src/styles/_enhanced.scss in the plugin), as the theme's build can't `@use`
 * it. This fails when the copy here no longer matches GATE below.
 *
 * It only guards this side. Matter's own Jest test fails when Matter's gate
 * changes and its CHANGELOG line ("The enhanced gate") isn't updated; when that
 * line changes, update the copy in _matter.scss and GATE here together.
 */
/* eslint-disable no-console */
const fs = require('fs');
const path = require('path');

const GATE =
	':is(.is-enhanced, html.matter-js:is(:not(.matter-late), .matter-ready-{type}) *, .editor-styles-wrapper *)';

const source = fs.readFileSync(
	path.join(__dirname, '..', 'src', 'css', 'tools', 'mixins', '_matter.scss'),
	'utf8'
);
const copy = source
	.match(/@function matter-enhanced\(\$type\) \{\s*@return "([^"]*)";/)?.[1]
	?.replace(/#\{\$type\}/g, '{type}');

if (copy !== GATE) {
	console.error(
		`matter-enhanced() is not Matter's enhanced gate.\n  have: ${copy}\n  want: ${GATE}\nSee the "The enhanced gate" line in Matter's CHANGELOG.`
	);
	process.exit(1);
}
