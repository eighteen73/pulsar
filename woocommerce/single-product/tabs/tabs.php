<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

// Change tabs from ul/li and div to details/summary disclosure element.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', [] );

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-product-details-accordion">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<details name="single-product" class="woocommerce-product-details-accordion__item" id="accordion-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="accordion-<?php echo esc_attr( $key ); ?>">
					<summary class="woocommerce-product-details-accordion__item-title"><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></summary>
					<div class="woocommerce-product-details-accordion__item-content">
						<?php
						if ( isset( $product_tab['callback'] ) ) {
							call_user_func( $product_tab['callback'], $key, $product_tab );
						}
						?>
					</div>
				</details>
			<?php endforeach; ?>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>
