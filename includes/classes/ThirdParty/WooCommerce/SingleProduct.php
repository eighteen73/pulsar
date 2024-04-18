<?php
/**
 * WooCommerce single product class.
 *
 * @package Pulsar
 */

namespace Pulsar\ThirdParty\WooCommerce;

use Pulsar\Contracts\Bootable;

/**
 * Handles the single product.
 *
 * @access public
 */
class SingleProduct implements Bootable {

	/**
	 * Adds actions on the appropriate action hooks.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {

		// Remove the product description heading.
		add_filter( 'woocommerce_product_description_heading', '__return_null' );
	}
}
