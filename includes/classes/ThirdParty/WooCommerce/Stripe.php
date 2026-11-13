<?php
/**
 * WooCommerce Stripe: its card fields in the theme's style.
 *
 * @package Pulsar
 */

namespace Pulsar\ThirdParty\WooCommerce;

use Pulsar\Contracts\Bootable;

/**
 * Gives Stripe's payment fields the theme's appearance (build/js/stripe.js).
 */
class Stripe implements Bootable {

	/**
	 * Stripe's scripts that bring its parameters: the payment form's
	 * (`wc_stripe_upe_params`, on the block checkout as well as the classic
	 * pages) and the express payment buttons' (`wc_stripe_express_checkout_params`).
	 */
	private const STRIPE_HANDLES = [ 'wc-stripe-upe-classic', 'wc_stripe_express_checkout' ];

	/**
	 * Adds actions on the appropriate action hooks.
	 *
	 * @return void
	 */
	public function boot(): void {
		// After Stripe enqueues its script (`wp_footer`, 10), before the
		// footer's scripts print (20).
		add_action( 'wp_footer', [ $this, 'appearance' ], 11 );
	}

	/**
	 * Checks if WooCommerce Stripe is active.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return class_exists( 'WC_Stripe' );
	}

	/**
	 * The appearance, after Stripe's parameters, where Stripe's fields or
	 * buttons are (after only the scripts the page has, so as not to add one).
	 *
	 * @return void
	 */
	public function appearance(): void {
		$stripe = array_values( array_filter( self::STRIPE_HANDLES, fn( string $handle ): bool => wp_script_is( $handle ) ) );

		if ( ! $stripe ) {
			return;
		}

		$asset = require get_theme_file_path( 'build/js/stripe.asset.php' );

		wp_enqueue_script(
			'pulsar-stripe',
			get_theme_file_uri( 'build/js/stripe.js' ),
			array_merge( $asset['dependencies'], $stripe ),
			$asset['version'],
			[ 'in_footer' => true ]
		);
	}
}
