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
		add_filter( 'woocommerce_get_price_html', [ $this, 'from_variation_price' ], 10, 2 );
		add_filter( 'woocommerce_style_smallscreen_breakpoint', [ $this, 'smallscreen_breakpoint' ] );
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
	 * Multiples of 12 is ideal depending on the number of columns in the grid - this works for 2, 3 and 4 columns.
	 *
	 * @param \WP_Query $query The WP_Query instance.
	 * @return void
	 */
	public function products_per_page( \WP_Query $query ): void {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( is_post_type_archive( 'product' ) ) {
			$query->set( 'posts_per_page', 24 );
		}
	}

	/**
	 * WooCommerce's small screen styles (its stacked tables) up to the theme's
	 * `md` breakpoint, where the theme's `--md-down` queries end, rather than
	 * at 768px inclusive.
	 *
	 * @param string $breakpoint WooCommerce's breakpoint (a max-width).
	 * @return string
	 */
	public function smallscreen_breakpoint( string $breakpoint ): string {
		$breakpoints = wp_json_file_decode( get_theme_file_path( 'config/breakpoints.json' ), [ 'associative' => true ] );

		return isset( $breakpoints['md'] ) ? ( (int) $breakpoints['md'] - 1 ) . 'px' : $breakpoint;
	}

	/**
	 * Adjust a variable product price to show From {price}
	 *
	 * @param string                           $price The price html.
	 * @param \WC_Product|\WC_Product_Variable $product The product object.
	 * @return string
	 */
	public function from_variation_price( string $price, \WC_Product|\WC_Product_Variable $product ): string {
		if ( $product->is_type( 'variable' ) && $product->get_variation_price( 'min' ) !== $product->get_variation_price( 'max' ) ) {
			return '<span class="wc-block-components-product-price__prefix">From </span>' . wc_price( $product->get_variation_price( 'min' ) );
		}

		return $price;
	}
}
