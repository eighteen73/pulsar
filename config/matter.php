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
 * (int|'auto'), autoplay (seconds, 0 = off), arrows (bool), dots (bool).
 * Gallery options: imageSize (a registered image size), plus the carousel
 * options for its carousel layout.
 *
 * @package Pulsar
 */

return [
	'matter/carousel' => [
		'default' => [
			'label' => __( 'Single', 'pulsar' ),
		],
		'cards'   => [
			'label'          => __( 'Cards', 'pulsar' ),
			'slidesToScroll' => 'auto',
		],
		'slides'  => [
			'label'    => __( 'Slideshow', 'pulsar' ),
			'loop'     => true,
			'autoplay' => 6,
			'arrows'   => false,
		],
	],
	'matter/gallery'  => [
		// Gallery variants set the image size here and the crop ratio in
		// src/css/blocks/matter-gallery.scss. Carousel options apply to the
		// gallery's carousel layout.
		'default'   => [
			'label'          => __( 'Square', 'pulsar' ),
			'imageSize'      => 'medium_large',
			'slidesToScroll' => 'auto',
		],
		'landscape' => [
			'label'     => __( 'Landscape', 'pulsar' ),
			'imageSize' => 'large',
		],
	],
];
