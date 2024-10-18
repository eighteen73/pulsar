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

		// Check if Gravity Forms is active.
		if ( ! class_exists( 'GFForms' ) ) {
			return;
		}

		// Disable Gravity Forms styles.
		add_filter( 'gform_disable_css', '__return_true' );

		// Change Gravity Forms submit button to a button element.
		// This allows us to use pseudo elements to style the button.
		add_filter( 'gform_submit_button', [ $this, 'input_to_button' ], 10, 2 );
	}

	/**
	 * Converts inputs to buttons so they can have pseudo elements applied.
	 *
	 * @param string $button The button element.
	 * @param array  $form   The form data.
	 *
	 * @return string
	 */
	public function input_to_button( $button, $form ): string {
		// save attribute string to $button_match[1]
		preg_match( '/<input([^\/>]*)(\s\/)*>/', $button, $button_match );

		$button_text = ! empty( $form['button']['text'] ) ? $form['button']['text'] : __( 'Submit', 'pulsar' );

		// remove value attribute (since we aren't using an input)
		$button_atts = str_replace( "value='" . $form['button']['text'] . "' ", '', $button_match[1] );

		// create the button element with the button text inside the button element instead of set as the value
		return '<button onclick=this.classList.add("is-loading") ' . $button_atts . '><span class="gform_button__text">' . esc_html( $button_text ) . '</span><span class="gform_button__loading"></span></button>';
	}
}
