<?php
/**
 * Matter block variants.
 *
 * Variants are shown to editors as block styles. Their options set behaviour;
 * their look (slide widths, sizes…) is styled in src/css/blocks/matter-*.scss
 * via `.is-style-{slug}`. Each block's `default` variant inherits Matter's
 * defaults; others inherit `default`.
 *
 * Carousel options: loop (bool), align (start|center|end), slidesToScroll
 * (int|'auto'), autoplay (seconds per slide, 0 = off), autoScroll (continuous
 * speed, 0 = off), controls (groups of parts by position: before, stage —
 * over the slides — and after; parts: prev, next, dots, progress, timer,
 * counter, play, thumbnails, expand), thumbnailSize. Place and style the
 * parts in src/css/blocks/matter-carousel.scss.
 * Carousels of only images also take aspectRatio: the default crop, a slug
 * from theme.json's aspect ratio presets or 'auto' (editors can change it).
 * Gallery options: imageSize (a registered image size), columns (the column
 * counts editors can choose; [] to set columns in CSS only), defaultColumns,
 * aspectRatio (as above), plus the carousel options for its carousel layout.
 * Tabs options: dropdownBelow (a breakpoint from breakpoints.json, or false
 * to switch `.wp-block-matter-tabs.has-dropdown` in your own CSS) — used by
 * tabs set to "Show as a dropdown on smaller screens".
 *
 * @package Pulsar
 */

return [
	'matter/carousel' => [
		'default' => [
			'label'       => __( 'Single', 'pulsar' ),
			'aspectRatio' => '16-9',
		],
		'cards'   => [
			'label'          => __( 'Cards', 'pulsar' ),
			'slidesToScroll' => 'auto',
		],
		'slides'  => [
			'label'    => __( 'Slideshow', 'pulsar' ),
			'loop'     => true,
			'autoplay' => 6,
			'controls' => [ 'after' => [ 'dots', 'play' ] ],
		],
		// Reference variants (see src/css/blocks/matter-carousel.scss):
		// keep, restyle or delete per project.
		'hero'    => [
			'label'    => __( 'Hero', 'pulsar' ),
			'loop'     => true,
			'autoplay' => 6,
			'controls' => [ 'after' => [ 'prev', 'next', 'timer', 'play' ] ],
		],
		'ticker'  => [
			'label'       => __( 'Logo ticker', 'pulsar' ),
			'loop'        => true,
			'autoScroll'  => 1,
			'aspectRatio' => 'auto',
			'controls'    => [],
		],
		'peek'    => [
			'label'    => __( 'Peek', 'pulsar' ),
			'controls' => [
				'stage' => [ 'prev', 'next' ],
				'after' => [ 'dots' ],
			],
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
