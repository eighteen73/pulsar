<?php
/**
 * Functions that display or render the site logo.
 *
 * @package Pulsar
 */

namespace Pulsar;

/**
 * Displays the site logo.
 *
 * @param  array $args Arguments to pass to the final rendered logo.
 * @return void
 */
function site_logo( array $args = [] ): void {
	echo get_site_logo( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Returns the site logo HTML.
 *
 * The logo is either an SVG in assets/svg, named without its extension
 * (`brand/logo`, the default), which is inlined so it can use `currentColor`,
 * or an image file in assets/ with its extension (`images/brand/logo.png`; PNG,
 * JPEG, WebP, AVIF or GIF), output as an `<img>` with its size, and a
 * `logo@2x.png` beside it as its high-density source.
 *
 * @param  array $args Arguments to pass to the final rendered logo.
 * @return string
 */
function get_site_logo( array $args = [] ): string {

	$args = wp_parse_args(
		$args,
		[
			'logo'        => $args['logo'] ?? 'brand/logo',
			// Not a heading: every template has its own h1 (the hero, the
			// page's title, an archive's title), so the front page would
			// have two. Pass 'tag' to change it.
			'tag'         => 'div',
			'class'       => $args['class'] ?? 'site-header__logo',
			'link_class'  => $args['link_class'] ?? 'site-header__logo-link',
			'image_class' => $args['image_class'] ?? 'site-header__logo-image',
		]
	);

	$html  = '';
	$title = get_bloginfo( 'name', 'display' );
	$logo  = is_logo_image( (string) $args['logo'] )
		? get_logo_image( (string) $args['logo'], $args['image_class'], $title )
		: render_svg(
			$args['logo'],
			[
				'class' => $args['image_class'],
				'title' => $title,
			],
		);

	if ( $title && $logo ) {

		$link = sprintf(
			'<a class="%s" href="%s" rel="home">%s</a>',
			esc_attr( $args['link_class'] ),
			esc_url( home_url() ),
			$logo,
		);

		$html = sprintf(
			'<%1$s class="%2$s">%3$s</%1$s>',
			tag_escape( $args['tag'] ),
			"{$args['class']}",
			$link,
		);
	}

	return $html;
}

/**
 * Whether a logo is an image file (by its extension) rather than an SVG name.
 *
 * @param  string $file The logo.
 * @return bool
 */
function is_logo_image( string $file ): bool {
	return (bool) preg_match( '/\.(png|jpe?g|webp|avif|gif)$/i', $file );
}

/**
 * A logo that's an image file in assets/ (not an SVG), as an `<img>`.
 *
 * @param  string $file  The file, relative to assets/, with its extension.
 * @param  string $class The image's class.
 * @param  string $alt   Its alternative text (the site's name).
 * @return string The image, or '' when the file isn't an image file in assets/.
 */
function get_logo_image( string $file, string $class, string $alt ): string {
	$path = get_theme_file_path( "assets/{$file}" );

	if ( ! is_logo_image( $file ) || ! is_file( $path ) ) {
		return '';
	}

	$size   = wp_getimagesize( $path );
	$src    = get_theme_file_uri( "assets/{$file}" );
	$retina = preg_replace( '/(\.\w+)$/', '@2x$1', $file );
	$srcset = is_file( get_theme_file_path( "assets/{$retina}" ) )
		? sprintf( '%s 1x, %s 2x', esc_url( $src ), esc_url( get_theme_file_uri( "assets/{$retina}" ) ) )
		: '';

	return sprintf(
		'<img class="%1$s" src="%2$s"%3$s%4$s alt="%5$s" decoding="async">',
		esc_attr( $class ),
		esc_url( $src ),
		$srcset ? sprintf( ' srcset="%s"', esc_attr( $srcset ) ) : '',
		$size ? sprintf( ' width="%d" height="%d"', $size[0], $size[1] ) : '',
		esc_attr( $alt )
	);
}
