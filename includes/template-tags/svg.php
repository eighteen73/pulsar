<?php
/**
 * SVG template tags.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Tools\Svg;

/**
 * Renders SVG output.
 *
 * @access public
 * @param  string|int $file The SVG file. Also accepts an attachment ID.
 * @param  array      $args An array or arguements to apply to the SVG.
 * @return void
 */
function display_svg( string|int $file, array $args = [] ): void {
	$svg = new Svg( $file, $args );
	$svg->display();
}

/**
 * Returns SVG output.
 *
 * @access public
 * @param  string|int $file The SVG file. Also accepts an attachment ID.
 * @param  array      $args An array or arguements to apply to the SVG.
 * @return string
 */
function render_svg( string|int $file, array $args = [] ): string {
	$svg = new Svg( $file, $args );
	return $svg->render();
}
