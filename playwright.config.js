/**
 * Browser tests (npm run test:e2e), against wp-env's tests site, with Pulsar
 * active and Matter as a must-use plugin (see .wp-env.json). wp-scripts'
 * config logs in as admin.
 *
 * Ports 8890/8891, so this can run alongside Matter's own wp-env.
 */
process.env.WP_BASE_URL ??= 'http://localhost:8891';

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
		port: 8891,
	},
});
