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
		// Options for the gallery's carousel style.
		'default' => [
			'slidesToScroll' => 'auto',
		],
	],
];
