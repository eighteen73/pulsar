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

		// Quantity buttons.
		add_action( 'woocommerce_before_quantity_input_field', [ $this, 'quantity_minus_button' ], 10 );
		add_action( 'woocommerce_after_quantity_input_field', [ $this, 'quantity_plus_button' ], 10 );
		add_action( 'woocommerce_before_single_product', [ $this, 'quantity_buttons_script' ] );
		add_action( 'woocommerce_before_single_product', [ $this, 'variation_price_to_product_price' ] );
		add_action( 'woocommerce_before_single_product', [ $this, 'variation_description_to_product_description' ] );
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
	 * Quantity: Output minus button
	 *
	 * @return void
	 */
	public function quantity_minus_button(): void {
		echo '<button class="quantity__button quantity__button--minus">-</button>';
	}

	/**
	 * Quantity: Output plus button
	 *
	 * @return void
	 */
	public function quantity_plus_button() {
		echo '<button class="quantity__button quantity__button--plus">+</button>';
	}

	/**
	 * Quantity: buttons script
	 *
	 * @return void
	 */
	public function quantity_buttons_script() {
		wc_enqueue_js(
			"
			document.querySelector('form.cart').addEventListener('click', function(event) {
				if (event.target.classList.contains('quantity__button')) {
					event.preventDefault();
					var qty = event.target.closest('form.cart').querySelector('.qty');
					var val = parseFloat(qty.value);
					var max = parseFloat(qty.getAttribute('max'));
					var min = parseFloat(qty.getAttribute('min'));
					var step = parseFloat(qty.getAttribute('step'));
					if (event.target.classList.contains('quantity__button--plus')) {
						if (max && (max <= val)) {
							qty.value = max;
						} else {
							qty.value = val + step;
						}
					} else {
						if (min && (min >= val)) {
							qty.value = min;
						} else if (val > 1) {
							qty.value = val - step;
						}
					}
				}
			});
			"
		);
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

	/**
	 * Variation description to product description
	 * This is to ensure that the description is updated when a variation is selected.
	 *
	 * @return void
	 */
	public function variation_description_to_product_description() {
		global $product;
		$description = $product->get_description();

		wc_enqueue_js(
			"
			$(document).on('show_variation', 'form.cart', function( event, variation ) {
				if(variation.variation_description) $('.wp-block-post-content').html(variation.variation_description);
				$('.woocommerce-variation-description').hide();
			});
			$(document).on('hide_variation', 'form.cart', function( event, variation ) {
				$('.wp-block-post-content').html('" . $description . "');
			});
		"
		);
	}
}
