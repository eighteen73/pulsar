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
class Setup implements Bootable {

	/**
	 * Adds actions on the appropriate action hooks.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'pre_get_posts', [ $this, 'products_per_page' ] );
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
	 * Set the number of products per page.
	 *
	 * @param \WP_Query $query The WP_Query instance.
	 * @return void
	 */
	public function products_per_page( \WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( is_post_type_archive( 'product' ) ) {
			$query->set( 'posts_per_page', 12 );
		}
	}
}
