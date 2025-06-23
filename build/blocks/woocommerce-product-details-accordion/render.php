<?php
/**
 * All of the parameters passed to the function where this file is being required are accessible in this scope:
 *
 * @param array    $attributes   The array of attributes for this block.
 * @param string   $content      Rendered block output. ie. <InnerBlocks.Content />.
 * @param WP_Block $block        The instance of the WP_Block class that represents the block being rendered.
 *
 * @package Pulsar
 */

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$default_tabs = woocommerce_default_product_tabs();
$product_tabs = apply_filters( 'woocommerce_product_tabs', $default_tabs );

$open_multiple = $attributes['openMultiple'] ?? false;
$start_open    = $attributes['startOpen'] ?? false;

if ( ! empty( $product_tabs ) ) : ?>
	<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
		<?php foreach ( $product_tabs as $key => $product_accordion ) : ?>
			<?php $name = $open_multiple ? "accordion-item-{$key}" : 'accordion-item'; ?>

			<details
				class="wp-block-pulsar-woocommerce-product-details-accordion__details"
				name="<?php echo esc_attr( $name ); ?>"
				<?php if ( $start_open ) : ?>
					open
				<?php endif; ?>
			>
				<summary class="wp-block-pulsar-woocommerce-product-details-accordion__summary">
					<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_accordion['title'], $key ) ); ?>
				</summary>

				<div class="wp-block-pulsar-woocommerce-product-details-accordion__content">
					<?php
					if ( isset( $product_accordion['callback'] ) ) {
						call_user_func( $product_accordion['callback'], $key, $product_accordion );
					}
					?>
				</div>
			</details>
		<?php endforeach; ?>

		<?php $start_open = false; ?>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>
<?php else : ?>
	<div <?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>>
		<p><?php esc_html_e( 'No product tabs found.', 'pulsar-blocks' ); ?></p>
	</div>
<?php endif; ?>
