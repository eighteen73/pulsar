/**
 * Browser tests (npm run test:e2e), against wp-env's test site
 * (.wp-env.tests.json), with Pulsar active and Matter as a must-use plugin.
 * wp-scripts' config logs in as admin.
 *
 * Ports 8890 (development) and 8891 (tests), so this can run alongside
 * Matter's own wp-env. Every project copied from Pulsar has the same ports: to
 * run two at once, give one others in `.wp-env.override.json` and
 * `.wp-env.tests.override.json` ("port"), which the tests then use.
 * global-setup.js stops the run if the site isn't this project's.
 */
const fs = require('fs');
const path = require('path');

/**
 * The test site's port: its override's, else .wp-env.tests.json's.
 *
 * @return {number} Port.
 */
function testsPort() {
	for (const file of ['.wp-env.tests.override.json', '.wp-env.tests.json']) {
		try {
			const { port } = JSON.parse(
				fs.readFileSync(path.join(__dirname, file), 'utf8')
			);

			if (port) {
				return port;
			}
		} catch {
			// No override.
		}
	}

	return 8891;
}

process.env.WP_BASE_URL ??= `http://localhost:${testsPort()}`;

const { defineConfig } = require('@playwright/test');
const base = require('@wordpress/scripts/config/playwright.config.js');

module.exports = defineConfig({
	...base,
	testDir: './tests/e2e',
	globalSetup: require.resolve('./tests/e2e/global-setup.js'),
	use: {
		...base.use,
		baseURL: process.env.WP_BASE_URL,
	},
	webServer: {
		...base.webServer,
		command: 'npm run env:start:tests',
		port: Number(new URL(process.env.WP_BASE_URL).port || 80),
	},
});
