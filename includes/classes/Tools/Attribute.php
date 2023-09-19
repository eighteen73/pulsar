<?php
/**
 * Attribute class.
 *
 * This is an HTML attributes class system. The purpose is to provide devs a
 * system for adding filterable attributes.  This is sort of like `body_class()`,
 * `post_class()`, and `comment_class()` on steroids. However, it can handle
 * attributes for any elements.
 *
 * @package Pulsar
 * @link    https://themehybrid.com/hybrid-attr
 */

namespace Pulsar\Tools;

/**
 * Attributes class.
 */
class Attribute {

	/**
	 * The name/ID of the element (e.g., `sidebar`).
	 *
	 * @access protected
	 * @var    string
	 */
	protected string $name = '';

	/**
	 * A specific context for the element (e.g., `primary`).
	 *
	 * @access public
	 * @var    string
	 */
	protected string $context = '';

	/**
	 * The input attributes first passed in.
	 *
	 * @access protected
	 * @var    array
	 */
	protected array $input = [];

	/**
	 * Stored array of attributes.
	 *
	 * @access protected
	 * @var    array
	 */
	protected array $attr = [];

	/**
	 * Stored array of data.
	 *
	 * @access protected
	 * @var    array
	 */
	protected array $data = [];

	/**
	 * Outputs an HTML element's attributes.
	 *
	 * @access public
	 * @param  string $name The name/ID of the element (e.g., `sidebar`).
	 * @param  string $context A specific context for the element (e.g., `primary`).
	 * @param  array  $attr An array of attributes to pass in.
	 */
	public function __construct( string $name, string $context = '', array $attr = [] ) {

		$this->name    = $name;
		$this->context = $context;
		$this->input   = $attr;
	}

	/**
	 * When attempting to use the object as a string, return the attributes
	 * output as a string.
	 *
	 * @access public
	 * @return string
	 */
	public function __toString(): string {
		return $this->render();
	}

	/**
	 * Outputs an escaped string of attributes for use in HTML.
	 *
	 * @access public
	 * @return void
	 */
	public function display(): void {
		echo $this->render(); // PHPCS:ignore:WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Returns an escaped string of attributes for use in HTML.
	 *
	 * @access public
	 * @return string
	 */
	public function render(): string {

		$html = '';

		foreach ( $this->all() as $name => $value ) {

			$esc_value = '';

			// If the value is a link `href`, use `esc_url()`.
			if ( $value !== false && 'href' === $name ) {
				$esc_value = esc_url( $value );

			} elseif ( $value !== false ) {
				$esc_value = esc_attr( $value );
			}

			$html .= false !== $value ? sprintf( ' %s="%s"', esc_html( $name ), $esc_value ) : esc_html( " {$name}" );
		}

		return trim( $html );
	}

	/**
	 * Adds custom data to the attribute object.
	 *
	 * @access public
	 * @param  string|array $name The name(s) of the attribute(s).
	 * @param  mixed        $value The value of the attribute.
	 * @return $this
	 */
	public function with( string|array $name, mixed $value = null ) {

		if ( is_array( $name ) ) {
			$this->data = array_merge( $this->data, $name );
		} else {
			$this->data[ $name ] = $value;
		}

		return $this;
	}

	/**
	 * Returns a single, unescaped attribute's value.
	 *
	 * @access public
	 * @param  string $name The name of the attribute.
	 * @return string
	 */
	public function get( string $name ): string {

		$attr = $this->all();

		return isset( $attr[ $name ] ) ? $attr[ $name ] : '';
	}

	/**
	 * Filters and returns the array of attributes.
	 *
	 * @access public
	 * @return array
	 */
	public function all(): array {

		// If we already have attributes, let's return them and bail.
		if ( $this->attr ) {
			return $this->attr;
		}

		$defaults = [];

		// If the a class was input, we want to go ahead and set that as
		// the default class.  That way, filters can know early on that
		// a class has already been declared. Any filters on the defaults
		// should, ideally, respect any classes that already exist.
		if ( isset( $this->input['class'] ) ) {
			$defaults['class'] = $this->input['class'];

			// This is kind of a hacky way to keep the class input
			// from overwriting everything later.
			unset( $this->input['class'] );

			// If no class was input, let's build a custom default.
		} else {
			$defaults['class'] = $this->context ? "{$this->name} {$this->name}--{$this->context}" : $this->name;
		}

		// Compatibility with core WP attributes.
		if ( method_exists( $this, $this->name ) ) {
			$method   = $this->name;
			$defaults = $this->$method( $defaults );
		}

		// Merge the attributes with the defaults.
		$this->attr = wp_parse_args( $this->input, $defaults );

		if ( isset( $this->attr['class'] ) ) {

			$classes = explode( ' ', $this->attr['class'] );

			$this->attr['class'] = join( ' ', array_unique( $classes ) );
		}

		return $this->attr;
	}

	/**
	 * `<html>` element attributes.
	 *
	 * @access protected
	 * @param  array $attr Array of attributes.
	 * @return array
	 */
	protected function html( array $attr ): array {

		$attr = [];

		$parts = wp_kses_hair( get_language_attributes(), [ 'http', 'https' ] );

		if ( $parts ) {

			foreach ( $parts as $part ) {

				$attr[ $part['name'] ] = $part['value'];
			}
		}

		return $attr;
	}
}
