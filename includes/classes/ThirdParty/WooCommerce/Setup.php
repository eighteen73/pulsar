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
		add_filter( 'render_block_woocommerce/product-filters', [ $this, 'product_filters_default_styles' ] );
		add_filter( 'render_block_woocommerce/product-filter-checkbox-list', [ $this, 'rating_stars_role' ] );
	}

	/**
	 * The rating filter's stars are labelled ("Rated 4 out of 5") on a span
	 * with no role, where the label is ignored (and is an axe failure). As an
	 * image it is read, and names the checkbox. Every rating, including the
	 * template WooCommerce clones in the browser.
	 *
	 * @param string $content The checkbox list's HTML.
	 * @return string
	 */
	public function rating_stars_role( string $content ): string {
		$processor = new \WP_HTML_Tag_Processor( $content );

		while ( $processor->next_tag( [ 'class_name' => 'wc-block-product-filter-checkbox-list__stars' ] ) ) {
			$processor->set_attribute( 'role', 'img' );
		}

		return $processor->get_updated_html();
	}

	/**
	 * The inline styles WooCommerce gives the product filters' blocks when
	 * they're inserted, by the class of the element they're on: the title's
	 * and each filter's heading margins, and the "Clear filters" button's
	 * padding and border.
	 */
	private const PRODUCT_FILTERS_DEFAULT_STYLES = [
		'wp-block-heading'      => [ 'margin-top:0;margin-bottom:0', 'margin-top:0;margin-bottom:0.625rem' ],
		'wp-block-button__link' => [ 'border-width:1px;padding-top:5px;padding-right:8px;padding-bottom:5px;padding-left:8px;text-decoration:none' ],
	];

	/**
	 * The product filters are spaced and their "Clear filters" button styled
	 * by the theme (blocks/woocommerce/product-filters.scss and
	 * product-filter-clear-button.scss), so WooCommerce's inline styles go.
	 * Only while they're still WooCommerce's: a style set in the editor stays.
	 *
	 * @param string $content The product filters' HTML.
	 * @return string
	 */
	public function product_filters_default_styles( string $content ): string {
		$processor = new \WP_HTML_Tag_Processor( $content );

		while ( $processor->next_tag() ) {
			$style = $processor->get_attribute( 'style' );

			if ( ! is_string( $style ) ) {
				continue;
			}

			$style = rtrim( str_replace( [ ' ', "\n", "\t" ], '', $style ), ';' );

			foreach ( self::PRODUCT_FILTERS_DEFAULT_STYLES as $class => $defaults ) {
				if ( $processor->has_class( $class ) && in_array( $style, $defaults, true ) ) {
					$processor->remove_attribute( 'style' );
					break;
				}
			}
		}

		return $processor->get_updated_html();
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
