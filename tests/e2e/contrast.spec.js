/**
 * Every colour scheme's role pairs meet WCAG 2.2 AA contrast, before axe
 * finds a failure on a page that happens to use the pair. Schemes come from
 * theme.json, so a project's own are checked as it changes them.
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');
const { settings } = require('../../theme.json');

const SCHEMES = Object.keys(settings.custom.scheme);
const BACKGROUNDS = ['background', 'background-surface'];

/** [foreground, background, minimum ratio]. */
const PAIRS = [
	...['text', 'text-subtle', 'text-heading', 'text-link'].flatMap((role) =>
		BACKGROUNDS.map((background) => [role, background, 4.5])
	),
	['text-fill', 'background-fill', 4.5],
	...['border-strong', 'highlight'].flatMap((role) =>
		BACKGROUNDS.map((background) => [role, background, 3])
	),
];

test.describe('colour schemes', () => {
	test('role pairs meet AA contrast in every scheme', async ({ page }) => {
		await page.goto('/');

		const failures = await page.evaluate(
			({ schemes, pairs }) => {
				const canvas = document.createElement('canvas');
				const context = canvas.getContext('2d', {
					willReadFrequently: true,
				});
				const probe = document.createElement('div');

				canvas.width = 1;
				canvas.height = 1;
				document.body.append(probe);

				// A role's colour in sRGB: resolved by the browser (palette
				// colours may be color-mix()), drawn and read back.
				const rgb = (scheme, role) => {
					probe.className = `has-scheme has-scheme-${scheme}`;
					probe.style.color = `var(--scheme--${role})`;
					context.clearRect(0, 0, 1, 1);
					context.fillStyle = window.getComputedStyle(probe).color;
					context.fillRect(0, 0, 1, 1);

					return [
						...context.getImageData(0, 0, 1, 1).data.slice(0, 3),
					];
				};

				const luminance = (channels) => {
					const [r, g, b] = channels.map((value) => {
						const channel = value / 255;

						return channel <= 0.04045
							? channel / 12.92
							: ((channel + 0.055) / 1.055) ** 2.4;
					});

					return 0.2126 * r + 0.7152 * g + 0.0722 * b;
				};

				const ratio = (a, b) => {
					const [light, dark] = [luminance(a), luminance(b)].sort(
						(x, y) => y - x
					);

					return (light + 0.05) / (dark + 0.05);
				};

				const found = [];

				for (const scheme of schemes) {
					for (const [foreground, background, minimum] of pairs) {
						const value = ratio(
							rgb(scheme, foreground),
							rgb(scheme, background)
						);

						if (value < minimum) {
							found.push(
								`${scheme}: ${foreground} on ${background} is ${value.toFixed(2)}:1 (needs ${minimum}:1)`
							);
						}
					}
				}

				probe.remove();

				return found;
			},
			{ schemes: SCHEMES, pairs: PAIRS }
		);

		expect(failures).toEqual([]);
	});
});
