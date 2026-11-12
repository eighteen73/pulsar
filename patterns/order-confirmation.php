<?php
/**
 * Title: Order confirmation
 * Slug: pulsar/order-confirmation
 * Description: The order received page (templates/order-confirmation.html), from WooCommerce's order confirmation blocks.
 * Inserter: false
 *
 * @package pulsar
 */

?>
<!-- wp:group {"align":"full","className":"has-scheme has-scheme-subtle","style":{"spacing":{"padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-scheme has-scheme-subtle" style="padding-top:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xl)">
	<!-- wp:woocommerce/order-confirmation-status {"align":"wide","fontSize":"2-xl"} /-->
</div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"order-confirmation","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide order-confirmation">
	<!-- wp:woocommerce/order-confirmation-summary {"align":"wide"} /-->

	<!-- wp:woocommerce/order-confirmation-totals-wrapper {"align":"wide"} -->
		<!-- wp:heading {"fontSize":"xl"} -->
		<h2 class="wp-block-heading has-xl-font-size"><?php esc_html_e( 'Order details', 'pulsar' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:woocommerce/order-confirmation-totals {"align":"wide","lock":{"remove":true}} /-->
	<!-- /wp:woocommerce/order-confirmation-totals-wrapper -->

	<!-- wp:woocommerce/order-confirmation-downloads-wrapper {"align":"wide"} -->
		<!-- wp:heading {"fontSize":"xl"} -->
		<h2 class="wp-block-heading has-xl-font-size"><?php esc_html_e( 'Downloads', 'pulsar' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:woocommerce/order-confirmation-downloads {"align":"wide","lock":{"remove":true}} /-->
	<!-- /wp:woocommerce/order-confirmation-downloads-wrapper -->

	<!-- wp:columns {"className":"is-stacked-on-md wc-block-order-confirmation-address-wrapper","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|2-xl","left":"var:preset|spacing|2-xl"}}}} -->
	<div class="wp-block-columns is-stacked-on-md wc-block-order-confirmation-address-wrapper">
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:woocommerce/order-confirmation-shipping-wrapper -->
				<!-- wp:heading {"fontSize":"xl"} -->
				<h2 class="wp-block-heading has-xl-font-size"><?php esc_html_e( 'Shipping address', 'pulsar' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:woocommerce/order-confirmation-shipping-address {"lock":{"remove":true}} /-->
			<!-- /wp:woocommerce/order-confirmation-shipping-wrapper -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:woocommerce/order-confirmation-billing-wrapper -->
				<!-- wp:heading {"fontSize":"xl"} -->
				<h2 class="wp-block-heading has-xl-font-size"><?php esc_html_e( 'Billing address', 'pulsar' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:woocommerce/order-confirmation-billing-address {"lock":{"remove":true}} /-->
			<!-- /wp:woocommerce/order-confirmation-billing-wrapper -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

	<!-- wp:woocommerce/order-confirmation-additional-fields-wrapper {"align":"wide"} -->
		<!-- wp:heading {"fontSize":"xl"} -->
		<h2 class="wp-block-heading has-xl-font-size"><?php esc_html_e( 'Additional information', 'pulsar' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:woocommerce/order-confirmation-additional-fields /-->
	<!-- /wp:woocommerce/order-confirmation-additional-fields-wrapper -->

	<!-- wp:woocommerce/order-confirmation-additional-information /-->
</div>
<!-- /wp:group -->
