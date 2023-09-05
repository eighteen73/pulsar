<?php
/**
 * Title: 404
 * Slug: pulsar/404
 * Description:
 * Categories: content
 * Inserter: false
 * Keywords: 404
 *
 * @package pulsar
 */

?>

<!-- wp:group {"tagName":"article","style":{"spacing":{"padding":{"top":"var:preset|spacing|5-xl","bottom":"var:preset|spacing|5-xl"}}},"layout":{"type":"constrained","contentSize":"800px"}} -->
<article class="wp-block-group" style="padding-top:var(--wp--preset--spacing--5-xl);padding-bottom:var(--wp--preset--spacing--5-xl)">
	<!-- wp:group {"tagName":"header","layout":{"type":"constrained"}} -->
	<header class="wp-block-group">
		<!-- wp:heading {"level":1} -->
		<h1 class="wp-block-heading"><?php echo esc_html__( '404: Nothing Found', 'pulsar' ); ?></h1>
		<!-- /wp:heading -->
	</header>
	<!-- /wp:group -->

	<!-- wp:group {"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph -->
		<p><?php echo esc_html__( 'It looks like you stumbled upon a page that does not exist. Perhaps a search might help:', 'pulsar' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:search {
			"label":"<?php echo esc_html__( 'Search', 'pulsar' ); ?>",
			"showLabel":false,
			"placeholder":"<?php echo esc_attr__( 'Enter search terms...', 'pulsar' ); ?>",
			"buttonText":"<?php echo esc_html__( 'Search', 'pulsar' ); ?>",
			"buttonPosition":"button-inside"
		} /-->
	</div>
	<!-- /wp:group -->
</article>
<!-- /wp:group -->
