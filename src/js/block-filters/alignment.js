import { addFilter } from '@wordpress/hooks';

const updateAlignment = (settings, name) => {
	switch (name) {
		case 'core/columns':
			return lodash.assign({}, settings, {
				supports: lodash.assign({}, settings.supports, {
					align: ['wide', 'full'],
				}),
				attributes: lodash.assign({}, settings.attributes, {
					align: {
						type: 'string',
						default: 'wide',
					},
				}),
			});

		case 'core/group':
			return lodash.assign({}, settings, {
				attributes: lodash.assign({}, settings.attributes, {
					align: {
						type: 'string',
						default: 'full',
					},
				}),
			});
	}

	return settings;
};

addFilter(
	'blocks.registerBlockType',
	'pulsar/set-block-alignment',
	updateAlignment
);
