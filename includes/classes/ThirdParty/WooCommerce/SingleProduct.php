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

		// Remove non-block related/upsell products.
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

		// Variation price.
		add_action( 'woocommerce_before_single_product', [ $this, 'variation_price_to_product_price' ] );

		// Remove the description heading.
		add_filter( 'woocommerce_product_description_heading', '__return_null' );

		// Remove the reviews heading.
		add_filter( 'woocommerce_product_reviews_heading', '__return_null' );

		// Remove the additional information heading.
		add_filter( 'woocommerce_product_additional_information_heading', '__return_null' );
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
	 * Variation price to product price
	 * This is to ensure that the price is updated when a variation is selected.
	 *
	 * @return void
	 */
	public function variation_price_to_product_price() {
		global $product;
		$price = $product->get_price_html();

		wc_enqueue_js(
			"
			$(document).on('show_variation', 'form.cart', function( event, variation ) {
				if(variation.price_html) $('[data-is-descendent-of-single-product-template=true] .wc-block-components-product-price').html(variation.price_html);
				$('.woocommerce-variation-price').hide();
			});
			$(document).on('hide_variation', 'form.cart', function( event, variation ) {
				$('[data-is-descendent-of-single-product-template=true] .wc-block-components-product-price').html('" . $price . "');
			});
			"
		);
	}
}
