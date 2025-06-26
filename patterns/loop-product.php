<?php
/**
 * Title: Loop Product
 * Slug: pulsar/loop-product
 * Description: A single product, for use in product collections.
 * Categories: woocommerce
 * Post Types:
 * Viewport Width:
 * Keywords: product
 * Inserter: false
 *
 * @package pulsar
 */

?>

<!-- wp:group {"className":"","style":{"spacing":{"blockGap":"var:preset|spacing|xl"}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
	<!-- wp:woocommerce/product-image {"saleBadgeAlign":"left","imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"height":"%","className":""} /-->

	<!-- wp:group {"className":"","style":{"spacing":{"blockGap":"var:preset|spacing|sm"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:post-title {"className":"","fontSize":"md","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->
		<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"className":"","textColor":"base-accent","fontSize":"lg","style":{"elements":{"link":{"color":{"text":"var:preset|color|base-accent"}}}}} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
