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
}
