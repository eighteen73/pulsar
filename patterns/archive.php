<?php
/**
 * Title: Archive
 * Slug: pulsar/archive
 * Description: Default archive content: the archive's title and description, then its list. Editors start from this when they customise an archive.
 * Inserter: false
 *
 * @package pulsar
 */

?>
<!-- wp:pulsar/text -->
<!-- wp:matter/slot {"name":"content"} -->
<!-- wp:heading {"level":1,"metadata":{"bindings":{"content":{"source":"matter/archive","args":{"key":"title"}}}}} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Archive title', 'pulsar' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"matter/archive","args":{"key":"description"}}}}} -->
<p></p>
<!-- /wp:paragraph -->
<!-- /wp:matter/slot -->
<!-- /wp:pulsar/text -->

<!-- wp:matter/archive-loop /-->
