<?php
/**
 * Blocks available to content editors in the post editor.
 *
 * Clients compose pages from developer-built sections and components, filling
 * them with core content blocks. Layout blocks (group, columns, cover etc.) are
 * deliberately excluded: layout decisions live in section blocks.
 *
 * Entries ending in `/*` allow every registered block in that namespace,
 * including child blocks (inner-only blocks are still restricted by their own
 * `parent`/`ancestor`/`inserter` settings).
 *
 * Allowing `matter/carousel` also allows its posts and products carousels
 * (Matter adds the Query Loop and Product Collection blocks they're made of).
 *
 * Templates and template parts are developer-owned files and may use any block.
 * Return an empty array to remove the restriction entirely.
 *
 * @see https://wordpress.org/documentation/article/blocks-list/
 *
 * @package Pulsar
 */

return [
	// Text.
	'core/heading',
	'core/paragraph',
	'core/list',
	'core/list-item',
	'core/quote',
	'core/table',

	// Media.
	'core/image',
	'core/video',
	'core/embed',

	// Actions.
	'core/buttons',
	'core/button',

	// Sections and components.
	'pulsar/*',
	'matter/*',
];
