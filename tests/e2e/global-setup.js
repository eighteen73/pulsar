/**
 * Before the browser tests: wp-scripts' set-up (log in as admin), Pulsar
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

module.exports = async (config) => {
	await baseSetup(config);

	const { storageState, baseURL } = config.projects[0].use;
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
			'npx wp-env run tests-cli wp eval-file wp-content/themes/pulsar/tests/wp-env/seed-extensions.php',
			{ stdio: 'inherit' }
		);
	}

	await context.dispose();
};
