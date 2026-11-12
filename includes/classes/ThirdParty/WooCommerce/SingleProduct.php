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

		// Variation price, for classic variation forms (e.g. extensions' product
		// types; the block's own form updates the price itself).
		add_action( 'wp_enqueue_scripts', [ $this, 'variation_price_to_product_price' ], 20 );

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
	 * Variation price to product price: a classic variation form (`form.cart`)
	 * shows the chosen variation's price in the product's price block rather
	 * than below the options. Attached to WooCommerce's variation script, so it
	 * only runs where that form is.
	 *
	 * @return void
	 */
	public function variation_price_to_product_price(): void {
		if ( ! \is_product() ) {
			return;
		}

		$product = \wc_get_product( get_queried_object_id() );

		if ( ! $product ) {
			return;
		}

		$selector = '[data-is-descendent-of-single-product-template=true] .wc-block-components-product-price';

		wp_add_inline_script(
			'wc-add-to-cart-variation',
			sprintf(
				'jQuery(function($){
					$(document).on("show_variation", "form.cart", function( event, variation ) {
						if ( variation.price_html ) $(%1$s).html( variation.price_html );
						$(".woocommerce-variation-price").hide();
					});
					$(document).on("hide_variation", "form.cart", function() {
						$(%1$s).html( %2$s );
					});
				});',
				wp_json_encode( $selector ),
				wp_json_encode( $product->get_price_html() )
			)
		);
	}
}
