/**
 * Editor: every section can be added and has its slots, without errors.
 */
const { test, expect } = require('@wordpress/e2e-test-utils-playwright');

/** Pulsar's sections (src/blocks), and the slots each should have. */
const SECTIONS = {
	'pulsar/hero': ['content', 'media'],
	'pulsar/text': ['content'],
	'pulsar/media-text': ['media', 'content'],
	'pulsar/cards': ['intro', 'items'],
	'pulsar/cta': ['content'],
};

test.describe('sections in the editor', () => {
	for (const [name, slots] of Object.entries(SECTIONS)) {
		test(`${name} can be added`, async ({ admin, editor, page }) => {
			const errors = [];

			page.on('pageerror', (error) => errors.push(error.message));

			await admin.createNewPost({ postType: 'page' });
			await editor.insertBlock({ name });

			await expect
				.poll(() =>
					page.evaluate(
						() =>
							window.wp.data
								.select('core/block-editor')
								.getBlocks()[0]
								?.innerBlocks.map(
									(slot) => slot.attributes.name
								) ?? []
					)
				)
				.toEqual(slots);

			// No "This block contains unexpected or invalid content" and no
			// block crashes.
			await expect(
				editor.canvas.locator(
					'.block-editor-warning, .block-editor-block-list__block-crash-warning'
				)
			).toHaveCount(0);
			expect(errors).toEqual([]);
		});
	}

	test('only the client palette is offered', async ({ admin, page }) => {
		await admin.createNewPost({ postType: 'page' });

		const allowed = await page.evaluate(() =>
			window.wp.data
				.select('core/block-editor')
				.getInserterItems('')
				.map(({ name }) => name)
		);

		expect(allowed).toEqual(
			expect.arrayContaining([
				'pulsar/hero',
				'core/paragraph',
				'matter/accordion',
			])
		);
		// Layout is the theme's: no groups or columns.
		expect(allowed).not.toContain('core/group');
		expect(allowed).not.toContain('core/columns');
	});
});
