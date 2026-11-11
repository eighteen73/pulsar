<?php
/**
 * Title: Page not found
 * Slug: pulsar/archive-404
 * Description: Default 404 content.
 * Inserter: false
 *
 * @package pulsar
 */

?>
<!-- wp:pulsar/text -->
<!-- wp:matter/slot {"name":"content"} -->
<!-- wp:heading {"level":1,"metadata":{"bindings":{"content":{"source":"matter/archive","args":{"key":"title"}}}}} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Page not found', 'pulsar' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'It looks like you stumbled upon a page that doesn’t exist.', 'pulsar' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'pulsar' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:matter/slot -->
<!-- /wp:pulsar/text -->
