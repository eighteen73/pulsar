<?php
/**
 * List all the blocks that you wish to be made available to content editors.
 *
 * Note: Custom blocks also need to be added to this array.
 *
 * A restrictive but useful default has been included.
 * Return an empty array if you wish to remove this restriction.
 *
 * @package Pulsar
 */

return [
	// Text
	'core/paragraph',
	'core/heading',
	'core/list',
	'core/quote',
	'core/preformatted',
	'core/pullquote',
	'core/table',

	// Media
	'core/image',
	'core/gallery',
	'core/cover',
	'core/file',
	'core/media-text',
	'core/video',

	// Widget
	'core/html',

	// Design
	'core/buttons',
	'core/button',
	'core/column',
	'core/columns',
	'core/group',
	'core/separator',
	'core/spacer',

	// Custom
	'pulsar/example-block',
];
