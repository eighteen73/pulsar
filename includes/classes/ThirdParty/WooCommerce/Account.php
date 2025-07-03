<?php
/**
 * WooCommerce setup class.
 *
 * @package Pulsar
 */

namespace Pulsar\ThirdParty\WooCommerce;

use Pulsar\Contracts\Bootable;

/**
 * Handles setting up everything we need for WooCommerce.
 *
 * @access public
 */
class Account implements Bootable {

	/**
	 * Adds actions on the appropriate action hooks.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'woocommerce_login_form_start', [ $this, 'login_intro_text' ] );
		add_action( 'woocommerce_register_form_start', [ $this, 'register_intro_text' ] );
	}

	/**
	 * Checks if WooCommerce is active.
	 *
	 * @access public
	 * @return bool
	 */
	public function can_boot(): bool {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Adds an intro text to the login form.
	 *
	 * @access public
	 * @return void
	 */
	public function login_intro_text(): void {
		echo '<p>' . esc_html__( 'Please enter your email & password to login', 'pulsar' ) . '</p>';
	}

	/**
	 * Adds an intro text to the register form.
	 *
	 * @access public
	 * @return void
	 */
	public function register_intro_text(): void {
		echo '<p>' . esc_html__( 'Creating an account is easy and only takes a few minutes', 'pulsar' ) . '</p>';
	}
}
