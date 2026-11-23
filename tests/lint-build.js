/**
 * The built stylesheets (npm run lint:build): none may start with a byte order
 * mark. Sass adds one to compressed CSS that has a character outside ASCII,
 * and when WordPress inlines the stylesheet the mark becomes part of its first
 * selector, which then never matches. Escape such characters in the SCSS
 * instead (e.g. `content: string.unquote('"\\00d7"')`).
 */
/* eslint-disable no-console */
const fs = require('fs');
const path = require('path');

const root = path.join(__dirname, '..', 'build', 'css');

/**
 * Every .css file under a folder.
 *
 * @param {string} dir Folder.
 * @return {string[]} Paths.
 */
const stylesheets = (dir) =>
	fs.readdirSync(dir, { withFileTypes: true }).flatMap((entry) => {
		const file = path.join(dir, entry.name);

		if (entry.isDirectory()) {
			return stylesheets(file);
		}

		return entry.name.endsWith('.css') ? [file] : [];
	});

const files = stylesheets(root);
const marked = files.filter((file) => {
	const start = fs.readFileSync(file).subarray(0, 3);

	return start[0] === 0xef && start[1] === 0xbb && start[2] === 0xbf;
});

if (marked.length) {
	console.error(
		`These stylesheets start with a byte order mark (a character outside ASCII in their SCSS):\n${marked
			.map((file) => `  ${path.relative(process.cwd(), file)}`)
			.join('\n')}`
	);
	process.exit(1);
}

console.log(`${files.length} stylesheets, none with a byte order mark.`);
