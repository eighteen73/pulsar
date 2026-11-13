/**
 * Browser tests (npm run test:e2e), against wp-env's tests site, with Pulsar
 * active and Matter as a must-use plugin (see .wp-env.json). wp-scripts'
 * config logs in as admin.
 *
 * Ports 8890/8891, so this can run alongside Matter's own wp-env. Every
 * project copied from Pulsar has the same ports: to run two at once, give one
 * others in `.wp-env.override.json` ("port", "testsPort"), which the tests
 * then use. global-setup.js stops the run if the site isn't this project's.
 */
const fs = require('fs');
const path = require('path');

/**
 * The tests site's port: the override's, else .wp-env.json's.
 *
 * @return {number} Port.
 */
function testsPort() {
	for (const file of ['.wp-env.override.json', '.wp-env.json']) {
		try {
			const { testsPort: port } = JSON.parse(
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
		command: 'npm run env:start',
		port: Number(new URL(process.env.WP_BASE_URL).port || 80),
	},
});
