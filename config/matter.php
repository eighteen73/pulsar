<?php
/**
 * Matter block variants.
 *
 * Variants are shown to editors as block styles. Their options set behaviour;
 * their look (slide widths, sizes…) is styled in src/css/blocks/matter/*.scss
 * via `.is-style-{slug}`. Each block's `default` variant inherits Matter's
 * defaults; others inherit `default`, so an option set on `default` applies to
 * every variant: put a crop, autoplay or loop on the variant that wants it.
 *
 * Carousel options: loop (bool), align (start|center|end), slidesToScroll
 * (int|'auto'), autoplay (seconds per slide, 0 = off), autoScroll (continuous
 * speed, 0 = off), controls (groups of parts by position: before, stage —
 * over the slides — and after; parts: prev, next, dots, progress, timer,
 * counter, play, thumbnails, expand), thumbnailSize. Place and style the
 * parts in src/css/blocks/matter/carousel.scss.
 * Carousels of only images also take aspectRatio: the default crop, a slug
 * from theme.json's aspect ratio presets or 'auto' (editors can change it).
 * Gallery options: imageSize (a registered image size), columns (the column
 * counts editors can choose; [] to set columns in CSS only), defaultColumns,
 * aspectRatio (as above), captions (the default caption style: 'tooltip',
 * 'overlay' or 'hidden'; editors can change it), plus the carousel options
 * for its carousel layout.
 * Tabs options: dropdownBelow (a breakpoint from breakpoints.json, or false
 * to switch `.wp-block-matter-tabs.has-dropdown` in your own CSS) — used by
 * tabs set to "Show as a dropdown on smaller screens".
 * Filters options: collapseBelow (a breakpoint from breakpoints.json below
 * which the filters are a drawer opened by the Filters button; '' for always
 * a drawer, false for never), position (the drawer's edge: start or end).
 * The listing's layout is components/_listing.scss, whose sidebar starts at
 * the same breakpoint.
 * Archive list (the posts or products in archive content): no options; each
 * variant is a layout for the template's list, styled in
 * src/css/blocks/matter/archive-loop.scss.
 *
 * @package Pulsar
 */

return [
	'matter/carousel' => [
		// Images keep their own shape unless an editor, or a variant, crops them.
		'default' => [
			'label'       => __( 'Single', 'pulsar' ),
			'aspectRatio' => 'auto',
		],
		'cards'   => [
			'label'          => __( 'Cards', 'pulsar' ),
			'slidesToScroll' => 'auto',
		],
		// One shape, so the height doesn't jump as slides change.
		'slides'  => [
			'label'       => __( 'Slideshow', 'pulsar' ),
			'loop'        => true,
			'autoplay'    => 6,
			'aspectRatio' => '16-9',
			'controls'    => [ 'before' => [ 'dots' ] ],
		],
	],
	'matter/archive-loop' => [
		'default' => [
			'label' => __( 'Three across', 'pulsar' ),
		],
		'four'    => [
			'label' => __( 'Four across', 'pulsar' ),
		],
		'feature' => [
			'label' => __( 'Feature first', 'pulsar' ),
		],
	],
	'matter/filters'  => [
		'default' => [
			'label'         => __( 'Sidebar', 'pulsar' ),
			'collapseBelow' => 'lg',
			'position'      => 'start',
		],
		// A row of buttons above the results, each opening its filter.
		'bar'     => [
			'label'         => __( 'Bar', 'pulsar' ),
			'collapseBelow' => 'lg',
			'position'      => 'start',
			'layout'        => 'bar',
		],
	],
	'matter/tabs'     => [
		'default' => [
			'dropdownBelow' => 'md',
		],
	],
	'matter/gallery'  => [
		// Carousel options apply to the gallery's carousel layout.
		'default' => [
			'imageSize'      => 'large',
			'columns'        => [ 2, 3, 4 ],
			'defaultColumns' => 4,
			'aspectRatio'    => 'square',
			'slidesToScroll' => 'auto',
		],
		// Reference variant for the carousel layout (see
		// src/css/blocks/matter-gallery.scss).
		'thumbnails' => [
			'label'          => __( 'Thumbnails', 'pulsar' ),
			'slidesToScroll' => 1,
			'aspectRatio'    => '4-3',
			'thumbnailSize'  => 'medium',
			'controls'       => [
				'stage' => [ 'prev', 'next', 'expand' ],
				'after' => [ 'thumbnails' ],
			],
		],
	],
];
