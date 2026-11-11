<?php
/**
 * Title: Product loop item
 * Slug: pulsar/loop-item-product
 * Description: How one product shows in every product loop and carousel, through Matter's loop item block. Change it here and every list of products follows.
 * Inserter: false
 *
 * @package pulsar
 */

?>
<!-- wp:group {"className":"","style":{"spacing":{"blockGap":"var:preset|spacing|lg"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">

	<!-- wp:group {"className":"wc-block-product-template__image-btn-wrapper","layout":{"type":"default"}} -->
	<div class="wp-block-group wc-block-product-template__image-btn-wrapper">

		<!-- wp:woocommerce/product-image {"showSaleBadge":false,"saleBadgeAlign":"left","imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"height":"%","className":""} -->
			<!-- wp:woocommerce/product-sale-badge {"align":"right","className":""} /-->
		<!-- /wp:woocommerce/product-image -->

		<!-- wp:woocommerce/product-button {"isDescendentOfQueryLoop":true,"className":"is-style-fill","fontSize":"xs"} /-->

	</div>
	<!-- /wp:group -->

	<!-- wp:group {"className":"","style":{"spacing":{"blockGap":"var:preset|spacing|sm"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">

		<!-- wp:post-title {"className":"","fontSize":"lg","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->

		<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"className":"","fontSize":"lg"} /-->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
