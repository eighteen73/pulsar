<?php
/**
 * SVG class.
 *
 * This is an SVG system for displaying SVGs in themes.
 *
 * @package Pulsar
 */

namespace Pulsar\Tools;

use Pulsar\Tools\Attribute;

/**
 * Create SVG markup from a .svg file.
 *
 * @param array $args The parameters needed to display the SVG.
 * @return string SVG markup.
 */
class Svg {

	/**
	 * The name of the SVG object.
	 *
	 * @var string
	 */
	protected string $name = '';

	/**
	 * The SVG file that we're getting. Use a relative path to the theme
	 * folder where the file is.
	 *
	 * @var string
	 */
	protected string $file = '';

	/**
	 * The class of the SVG object.
	 *
	 * @var string
	 */
	protected string $class = '';

	/**
	 * Used to add or replace an existing `<title>` element in the SVG.
	 *
	 * @var string
	 */
	protected string $title = '';

	/**
	 * Used to add or replace an existing `<desc>` element in the SVG.
	 *
	 * @var string
	 */
	protected string $desc = '';

	/**
	 * Path info about the file.
	 *
	 * @var array
	 */
	protected array $pathinfo = [];

	/**
	 * Sets up the object properties.
	 *
	 * @param  string $file The SVG file name.
	 * @param  array  $args An array of arguements to apply to the SVG.
	 * @return void
	 */
	public function __construct( string $file, array $args = [] ) {

		// If any of the arguments match a class property, set that
		// property to the argument value.
		$keys = array_keys( get_object_vars( $this ) );

		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}

		// Define the file property.
		$this->file = $file;

		// Get the file path info.
		$this->pathinfo = pathinfo( $this->file );

		// If the file has no extension, add a `.svg`.
		if ( ! isset( $this->pathinfo['extension'] ) ) {
			$this->file = "{$this->file}.svg";
		}

		// Get a name for use in hooks and such.
		$this->name = isset( $this->pathinfo['filename'] )
			? $this->pathinfo['filename']
			: basename( $this->file );

		// Setup classes to apply to the SVG.
		$this->class = isset( $this->class )
			? implode( ' ', [ $this->name, $this->class ] )
			: $this->name;
	}

	/**
	 * Returns the SVG output.
	 *
	 * @return string
	 */
	public function render() : string {

		$path = trim( 'assets/svg', '/' );

		$file = $path ? "{$path}/{$this->file}" : $this->file;

		$svg = file_get_contents( get_theme_file_path( $file ) );

		if ( ! $svg ) {
			return '';
		}

		// Get the attributes and inner HTML.
		preg_match( '/<svg(.*?)>(.*?)<\/svg>/is', $svg, $matches );

		if ( ! empty( $matches ) && isset( $matches[1] ) && isset( $matches[2] ) ) {

			$inner_html = $matches[2];

			// Create an array of existing attributes.
			$atts = wp_kses_hair( $matches[1], [ 'http', 'https' ] );

			// Sets up our attributes array.
			$attr = array_combine(
				array_column( $atts, 'name' ),
				array_column( $atts, 'value' )
			);

			// This doesn't actually help us in any way because we're
			// not building the `<title>` and `<desc>` elements.
			if ( $this->title ) {
				$unique_id = esc_attr( uniqid() );

				$attr['aria-labelledby'] = sprintf(
					$this->desc ? 'svg-title-%1$s svg-desc-%1$s' : 'svg-title-%s',
					$unique_id
				);

				$patterns = [
					'/<title.*?<\/title>/is',
					'/<desc.*?<\/desc>/is',
				];

				$inner_html = preg_replace( $patterns, '', $inner_html );

				$title_desc = sprintf(
					'<title id="svg-title-%s">%s</title>',
					$unique_id,
					esc_html( $this->title )
				);

				if ( $this->desc ) {
					$title_desc .= sprintf(
						'<desc id="svg-desc-%s">%s</desc>',
						$unique_id,
						esc_html( $this->desc )
					);
				}

				$inner_html = $title_desc . $inner_html;

			} else {
				$attr['aria-hidden'] = 'true';
				$attr['focusable']   = 'false';
			}

			$attr['role'] = 'img';

			// Get an attributes object.
			$attr = new Attribute( 'svg', $this->class, $attr );

			$svg = sprintf( '<svg %s>%s</svg>', $attr->render(), $inner_html );
		}

		return $svg;
	}

	/**
	 * Renders the SVG output.
	 *
	 * @return void
	 */
	public function display() : void {
		echo $this->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
