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

<!-- wp:group {"tagName":"main","className":"site-main","layout":{"type":"constrained"}} -->
<main class="wp-block-group site-main">
	<!-- wp:group {"className":"","style":{"spacing":{"padding":{"top":"var:preset|spacing|4-xl","bottom":"var:preset|spacing|4-xl"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--4-xl);padding-bottom:var(--wp--preset--spacing--4-xl)">
		<!-- wp:heading {"textAlign":"center","level":1,"className":""} -->
		<h1 class="wp-block-heading has-text-align-center"><?php echo esc_html_e( '404: Nothing Found', 'pulsar' ); ?></h1>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"align":"center","className":""} -->
		<p class="has-text-align-center"><?php echo esc_html_e( 'It looks like you stumbled upon a page that does not exist.', 'pulsar' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</main>
<!-- /wp:group -->
