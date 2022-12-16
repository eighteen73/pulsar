<?php
/**
 * @package Pulsar
 */

namespace Pulsar;

/**
 * Displays the site logo.
 *
 * @access public
 * @param  array $args
 * @return void
 */
function site_logo( array $args = [] ) {
	echo get_site_logo( $args );
}

/**
 * Returns the site logo HTML.
 *
 * @access public
 * @param  array $args
 * @return string
 */
function get_site_logo( array $args = [] ) {

	$args = wp_parse_args(
		$args,
		[
			'logo'        => $args['logo'] ?? 'logo',
			'tag'         => is_front_page() ? 'h1' : 'div',
			'class'       => $args['class'] ?? 'site-header__logo',
			'link_class'  => 'site-header__logo-link',
			'image_class' => 'site-header__logo-image',
		]
	);

	$html  = '';
	$title = get_bloginfo( 'name', 'display' );
	$logo  = render_svg(
		$args['logo'],
		[
			'class' => 'site-header__icon',
			'title' => $title,
		]
	);

	if ( $title && $logo ) {

		$link = sprintf(
			'<a class="%s" href="%s" rel="home">%s</a>',
			esc_attr( $args['link_class'] ),
			esc_url( home_url() ),
			$logo
		);

		$html = sprintf(
			'<%1$s class="%2$s">%3$s</%1$s>',
			tag_escape( $args['tag'] ),
			"{$args['class']}",
			$link
		);
	}

	return $html;
}
