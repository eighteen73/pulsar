import { addFilter } from '@wordpress/hooks';

/**
 * Make the Mega Menu Block available to Navigation blocks.
 *
 * @param {Object} blockSettings The original settings of the block.
 * @param {string} blockName     The name of the block being modified.
 * @return {Object}              The modified settings for the Navigation block or the original settings for other blocks.
 */
const addToNavigation = (blockSettings, blockName) => {
	if (blockName === 'core/navigation') {
		return {
			...blockSettings,
			allowedBlocks: [
				...(blockSettings.allowedBlocks ?? []),
				'pulsar/mega-menu',
			],
		};
	}
	return blockSettings;
};
addFilter(
	'blocks.registerBlockType',
	'pulsar-mega-menu-add-to-navigation',
	addToNavigation
);
