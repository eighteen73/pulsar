import { registerBlockType } from '@wordpress/blocks';
import { symbolFilled } from '@wordpress/icons';

import json from './block.json';
import Edit from './edit';

import './style.scss';

const { name } = json;

registerBlockType(name, {
	...json,
	icon: symbolFilled,
	edit: Edit,
});
