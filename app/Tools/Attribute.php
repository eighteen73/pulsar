<?php
/**
 * Attribute class.
 *
 * This is an HTML attributes class system. The purpose is to provide devs a
 * system for adding filterable attributes.  This is sort of like `body_class()`,
 * `post_class()`, and `comment_class()` on steroids. However, it can handle
 * attributes for any elements.
 *
 * @package NewTheme
 * @link    https://themehybrid.com/hybrid-attr
 */

namespace NewTheme\Tools;

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
	protected $name = '';

	/**
	 * A specific context for the element (e.g., `primary`).
	 *
	 * @access public
	 * @var    string
	 */
	protected $context = '';

	/**
	 * The input attributes first passed in.
	 *
	 * @access protected
	 * @var    array
	 */
	protected $input = [];

	/**
	 * Stored array of attributes.
	 *
	 * @access protected
	 * @var    array
	 */
	protected $attr = [];

	/**
	 * Stored array of data.
	 *
	 * @access protected
	 * @var    array
	 */
	protected $data = [];

	/**
	 * Outputs an HTML element's attributes.
	 *
	 * @access public
	 * @param  string $name The name/ID of the element (e.g., `sidebar`).
	 * @param  string $context A specific context for the element (e.g., `primary`).
	 * @param  array  $attr An array of attributes to pass in.
	 * @return void
	 */
	public function __construct( $name, $context = '', array $attr = [] ) {

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
	public function __toString() {
		return $this->render();
	}

	/**
	 * Outputs an escaped string of attributes for use in HTML.
	 *
	 * @access public
	 * @return void
	 */
	public function display() {
		echo $this->render(); // WPCS: XSS ok.
	}

	/**
	 * Returns an escaped string of attributes for use in HTML.
	 *
	 * @access public
	 * @return string
	 */
	public function render() {

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
	public function with( $name, $value = null ) {

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
	public function get( $name ) {

		$attr = $this->all();

		return isset( $attr[ $name ] ) ? $attr[ $name ] : '';
	}

	/**
	 * Filters and returns the array of attributes.
	 *
	 * @access protected
	 * @return array
	 */
	public function all() {

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
	protected function html( $attr ) {

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
