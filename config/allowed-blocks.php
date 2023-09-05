<?php
/**
 * List all the blocks that you wish to be made available to content editors.
 *
 * Note: Custom blocks also need to be added to this array.
 *
 * A restrictive but useful default has been included.
 * Return an empty array if you wish to remove this restriction.
 *
 * @see https://wordpress.org/documentation/article/blocks-list/
 *
 * @package Pulsar
 */

return [
	// Text
	'core/heading',
	'core/list',
	'core/list-item',
	'core/paragraph',
	'core/quote',
	'core/table',

	// Media
	'core/cover',
	'core/embed',
	'core/gallery',
	'core/image',
	'core/media-text',
	'core/video',

	// Design
	'core/button',
	'core/buttons',
	'core/column',
	'core/columns',
	'core/group',

	// Theme
	'core/archive-title',
	'core/post-title',
	'core/post-excerpt',
	'core/post-featured-image',
	'core/post-content',
	'core/query-loop',
	'core/query-title',
	'core/search',
	'core/search-results-title',
	'core/template-part',

	// WooCommerce

	// Pulsar
	'pulsar/template-part',
];
