import { addFilter } from '@wordpress/hooks';

/**
 * Helper function to update the align attribute.
 *
 * @param {Object} settings The block settings.
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
 * Set the default align attribute for blocks.
 *
 * @param {Object} settings The block settings.
 * @param {string} name The block name.
 *
 * @return {Object} The updated settings.
 */
const setDefaultBlockAlign = (settings, name) => {
	switch (name) {
		case 'core/columns':
			return updateAlign(settings, 'wide');

		case 'core/media-text':
			return updateAlign(settings, 'wide');

		case 'core/group':
			return updateAlign(settings, 'full');
	}

	return settings;
};

addFilter(
	'blocks.registerBlockType',
	'pulsar/set-default-align',
	setDefaultBlockAlign
);
