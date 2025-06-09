import { addFilter } from '@wordpress/hooks';

/**
 * Helper function to update the align attribute.
 *
 * @param {Object} settings  The block settings.
 * @param {string} alignment The alignment value.
 *
 * @return {Object} The updated settings.
 */
const updateAlign = (settings, alignment) => {
	return {
		...settings,
		attributes: {
			...settings.attributes,
			align: {
				type: 'string',
				default: alignment,
			},
		},
	};
};

/**
 * Update the align attribute for a specific variation.
 *
 * @param {Object} settings      The block settings.
 * @param {string} variationName The name of the variation to update.
 * @param {string} alignValue    The align value to set.
 *
 * @return {Object} The updated settings.
 */
const updateVariationAlign = (settings, variationName, alignValue) => {
	if (settings.variations) {
		settings.variations = settings.variations.map((variation) => {
			if (variation.name === variationName && variation.attributes) {
				variation.attributes.align = alignValue;
			}
			return variation;
		});
	}
	return settings;
};

/**
 * Set the default align attribute for blocks.
 *
 * @param {Object} settings The block settings.
 * @param {string} name     The block name.
 *
 * @return {Object} The updated settings.
 */
const setDefaultBlockAlign = (settings, name) => {
	switch (name) {
		case 'core/columns':
			return updateAlign(settings, 'wide');

		case 'core/media-text':
			return updateAlign(settings, 'wide');

		case 'core/cover':
			return updateAlign(settings, 'full');

		case 'core/group':
			settings = updateVariationAlign(settings, 'group', 'full');
			settings = updateVariationAlign(settings, 'group-row', 'wide');
			settings = updateVariationAlign(settings, 'group-grid', 'wide');

			return settings;
	}

	return settings;
};

addFilter(
	'blocks.registerBlockType',
	'pulsar/set-default-align',
	setDefaultBlockAlign
);
