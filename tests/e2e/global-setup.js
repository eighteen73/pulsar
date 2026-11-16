/**
 * Before the browser tests: a check that the site is this project's, then
 * wp-scripts' set-up (log in as admin), Pulsar
 * active, then the content every test uses (tests/e2e/content.js), and the
 * extension tests' content when they're mapped in
 * (tests/wp-env/seed-extensions.php).
 */
const { execSync } = require('child_process');
const { request } = require('@playwright/test');
const { RequestUtils } = require('@wordpress/e2e-test-utils-playwright');
const baseSetup = require('@wordpress/scripts/config/playwright/global-setup.js');
const { createContent } = require('./content');
const { activeExtensions } = require('./extensions');

/**
 * Stop unless the site is this project's wp-env tests site. The set-up
 * replaces content, and every project copied from Pulsar has the same ports,
 * so without this a project whose wp-env didn't start would test, and
 * change, another's.
 *
 * @param {string} baseURL The site the tests use.
 */
function checkSite(baseURL) {
	let home;

	try {
		home = execSync(
			'npx wp-env --config=.wp-env.tests.json run cli wp option get home',
			{
				encoding: 'utf8',
				stdio: ['ignore', 'pipe', 'ignore'],
			}
		)
			.split('\n')
			.find((line) => line.startsWith('http'));
	} catch {
		// Not running.
	}

	if (!home) {
		throw new Error(
			`This project's wp-env test site isn't running (npm run env:start:tests). Not testing ${baseURL}, which may be another project's site.`
		);
	}

	if (new URL(home).origin !== new URL(baseURL).origin) {
		throw new Error(
			`The tests would run against ${baseURL}, but this project's tests site is ${home}. Set its port in .wp-env.tests.override.json (see the README), or WP_BASE_URL=${home}.`
		);
	}
}

module.exports = async (config) => {
	const { storageState, baseURL } = config.projects[0].use;

	checkSite(baseURL);
	await baseSetup(config);

	const context = await request.newContext({ baseURL });
	const requestUtils = new RequestUtils(context, {
		storageStatePath:
			typeof storageState === 'string' ? storageState : undefined,
	});

	await requestUtils.setupRest();
	await requestUtils.activateTheme('pulsar');
	await createContent(requestUtils);

	if ((await activeExtensions(requestUtils)).length) {
		execSync(
			'npx wp-env --config=.wp-env.tests.json run cli wp eval-file wp-content/themes/pulsar/tests/wp-env/seed-extensions.php',
			{ stdio: 'inherit' }
		);
	}

	await context.dispose();
};
