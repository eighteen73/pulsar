<?php
/**
 * Title: Product category loop item
 * Slug: pulsar/loop-item-term-product_cat
 * Description: How one product category shows in every list of categories (e.g. "Shop by category"), through Matter's loop item block in a Terms Query: its image (or the fill colour without one), with its name and "Shop now" over the foot. The name links to the category, across the whole card.
 * Inserter: false
 *
 * @package pulsar
 */

?>
<!-- wp:group {"className":"term-card","layout":{"type":"default"}} -->
<div class="wp-block-group term-card">

	<!-- wp:image {"aspectRatio":"1","scale":"cover","metadata":{"bindings":{"url":{"source":"pulsar/term-featured-image","args":{"size":"large"}},"alt":{"source":"core/term-data","args":{"field":"name"}}}},"className":"term-card__image"} -->
	<figure class="wp-block-image term-card__image"><img alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
	<!-- /wp:image -->

	<!-- wp:group {"className":"term-card__content","layout":{"type":"default"}} -->
	<div class="wp-block-group term-card__content">

		<!-- wp:term-name {"level":3,"isLink":true,"className":"term-card__name"} /-->

		<!-- wp:paragraph {"className":"term-card__cta"} -->
		<p class="term-card__cta"><?php esc_html_e( 'Shop now', 'pulsar' ); ?></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
