<?php
/**
 * WooCommerce class.
 *
 * @package Pulsar
 */

namespace Pulsar\WooCommerce;

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

		// Register theme support for WooCommerce features.
		add_action( 'after_setup_theme', [ $this, 'supports' ] );

		// Disable WooCommerce core styles.
		add_filter( 'woocommerce_enqueue_styles', [ $this, 'disable_core_styles' ] );
	}

	/**
	 * Add theme support for WooCommerce features.
	 *
	 * @link https://docs.woocommerce.com/document/woocommerce-theme-developer-handbook/
	 * @access public
	 * @return void
	 */
	public function supports() {

		add_theme_support(
			'woocommerce',
			[
				'thumbnail_image_width' => 300,
				'single_image_width'    => 600,

				'product_grid'          => [
					'default_rows'    => 4,
					'min_rows'        => 2,
					'max_rows'        => 8,
					'default_columns' => 4,
					'min_columns'     => 2,
					'max_columns'     => 6,
				],
			]
		);

		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Disable core WooCommerce stylesheets.
	 *
	 * @return array
	 */
	public function disable_core_styles() {
		return [];
	}
}
