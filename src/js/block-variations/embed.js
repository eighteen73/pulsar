import domReady from '@wordpress/dom-ready';
import {
	getBlockVariations,
	unregisterBlockVariation,
} from '@wordpress/blocks';

// For a full list of embeds, see this this link:
// https://wordpress.org/documentation/category/embed-blocks/
const availableEmbeds = ['vimeo', 'youtube'];

domReady(() => {
	const embedVariations = getBlockVariations('core/embed');
	embedVariations.forEach((blockVariation) => {
		if (!availableEmbeds.includes(blockVariation.name)) {
			unregisterBlockVariation('core/embed', blockVariation.name);
		}
	});
});
