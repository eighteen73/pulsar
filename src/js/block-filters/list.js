import domReady from '@wordpress/dom-ready';
import { addFilter } from '@wordpress/hooks';
import { assign } from 'lodash';

domReady(() => {
	/**
	 * Adds a default class to the list block.
	 * This may not be needed if the following is merged:
	 *
	 * @see https://github.com/WordPress/gutenberg/pull/42269
	 *
	 * @param {Object} settings The block settings
	 * @param {string} name     The block name
	 * @return {Object} The block settings
	 */
	function addListBlockClassName(settings, name) {
		if (name !== 'core/list') {
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
		'pulsar/class-names/list-block',
		addListBlockClassName
	);
});
