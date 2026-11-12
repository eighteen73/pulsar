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

		add_action( 'woocommerce_account_content', [ $this, 'content_title' ], PHP_INT_MIN );

		// The dashboard: an overview, before extensions' dashboard content.
		add_action( 'woocommerce_account_dashboard', [ $this, 'dashboard' ], 5 );
	}

	/**
	 * Checks if WooCommerce is active.
	 *
	 * @access public
	 * @return bool`
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

	/**
	 * The current section's title, at the top of the account content: the
	 * endpoint's title (e.g. "Subscription #123"), else its name in the
	 * navigation (extensions' sections, such as Memberships', and the
	 * dashboard).
	 *
	 * @access public
	 * @return void
	 */
	public function content_title(): void {
		$endpoint = WC()->query->get_current_endpoint();
		$title    = $endpoint ? WC()->query->get_endpoint_title( $endpoint ) : '';

		// The navigation is keyed by the endpoint, or by its slug (Memberships'
		// `members_area` is `members-area` there).
		if ( '' === $title ) {
			$items = wc_get_account_menu_items();
			$slug  = WC()->query->get_query_vars()[ $endpoint ] ?? '';
			$title = $items[ $endpoint ?: 'dashboard' ] ?? $items[ $slug ] ?? '';
		}

		if ( '' === $title ) {
			return;
		}

		echo '<h2 class="my-account-title">' . esc_html( $title ) . '</h2>';
	}

	/**
	 * The dashboard's overview (template-parts/woocommerce/account-dashboard.php).
	 *
	 * @access public
	 * @return void
	 */
	public function dashboard(): void {
		get_template_part( 'template-parts/woocommerce/account-dashboard' );
	}
}
