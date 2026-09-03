<?php
/**
 * Gravity Forms functions and filters.
 *
 * @package Pulsar
 */

namespace Pulsar\ThirdParty;

use Pulsar\Contracts\Bootable;

/**
 * Gravity Forms class.
 *
 * @access public
 */
class GravityForms implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {

		// Disable Gravity Forms styles.
		add_filter( 'gform_disable_css', '__return_true' );

		// Change Gravity Forms submit button to a button element.
		add_filter( 'gform_next_button', [ $this, 'input_to_button' ], 10, 2 );
		add_filter( 'gform_previous_button', [ $this, 'input_to_button' ], 10, 2 );
		add_filter( 'gform_submit_button', [ $this, 'input_to_button' ], 10, 2 );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return class_exists( 'GFForms' );
	}

	/**
	 * Filters the next, previous and submit buttons.
	 * Replaces the form's <input> buttons with <button> while maintaining attributes from original <input>.
	 *
	 * @param string $button Contains the <input> tag to be filtered.
	 * @param array  $form    Contains all the properties of the current form.
	 *
	 * @return string The filtered button.
	 */
	public function input_to_button( $button, $form ): string {
		$fragment = \WP_HTML_Processor::create_fragment( $button );
		$fragment->next_token();

		$attributes      = [ 'id', 'type', 'class', 'onclick' ];
		$data_attributes = $fragment->get_attribute_names_with_prefix( 'data-' );
		if ( ! empty( $data_attributes ) ) {
			$attributes = array_merge( $attributes, $data_attributes );
		}

		$new_attributes = [];
		foreach ( $attributes as $attribute ) {
			$value = $fragment->get_attribute( $attribute );
			if ( ! empty( $value ) ) {
				$new_attributes[] = sprintf( '%s="%s"', $attribute, esc_attr( $value ) );
			}
		}

		return sprintf( '<button %s><span>%s</span></button>', implode( ' ', $new_attributes ), esc_html( $fragment->get_attribute( 'value' ) ) );
	}
}
