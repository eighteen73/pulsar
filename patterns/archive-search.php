<?php
/**
 * Title: Search results
 * Slug: pulsar/archive-search
 * Description: Default search results content: the title, then the search form and results.
 * Categories: content
 * Inserter: false
 *
 * @package pulsar
 */

?>
<!-- wp:pulsar/text -->
<!-- wp:matter/slot {"name":"content"} -->
<!-- wp:heading {"level":1,"metadata":{"bindings":{"content":{"source":"matter/archive","args":{"key":"title"}}}}} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Search results', 'pulsar' ); ?></h1>
<!-- /wp:heading -->
<!-- /wp:matter/slot -->
<!-- /wp:pulsar/text -->

<!-- wp:matter/archive-loop /-->
