import { addFilter } from '@wordpress/hooks';
import { assign } from 'lodash'; // eslint-disable-line import/no-extraneous-dependencies

/**
 * Adds a default class to the paragraph block.
 * This may not be needed if the following is merged:
 *
 * @see https://github.com/WordPress/gutenberg/pull/42269
 *
 * @param {Object} settings The block settings
 * @param {string} name     The block name
 * @return {Object} The block settings
 */
function groupSupports(settings, name) {
	if (name !== 'core/group') {
		return settings;
	}

	return assign({}, settings, {
		supports: assign({}, settings.supports, {
			spacing: {
				padding: ['top', 'bottom'],
			},
		}),
	});
}

addFilter(
	'blocks.registerBlockType',
	'pulsar/group-block/supports',
	groupSupports
);
