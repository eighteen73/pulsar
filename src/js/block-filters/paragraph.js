import { addFilter } from '@wordpress/hooks';
import { assign } from 'lodash';

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
function addParagraphBlockClassName(settings, name) {
	if (name !== 'core/paragraph') {
		return settings;
	}

	return assign({}, settings, {
		supports: assign({}, settings.supports, {
			className: true,
		}),
	});
}

addFilter(
	'blocks.registerBlockType',
	'pulsar/paragraph-block/class-names',
	addParagraphBlockClassName
);
