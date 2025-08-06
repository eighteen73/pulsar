<?php
/**
 * Title: Loop Pagination
 * Slug: pulsar/loop-pagination
 * Description: Pagination for use in loops.
 * Categories: theme
 * Post Types:
 * Viewport Width:
 * Keywords: pagination
 * Inserter: false
 *
 * @package pulsar
 */

?>

<!-- wp:group {"metadata":{"name":"Pagination"},"className":"","style":{"spacing":{"margin":{"top":"var:preset|spacing|4-xl"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--4-xl)">
	<!-- wp:query-pagination {"paginationArrow":"arrow","className":"","layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous {"className":""} /-->
		<!-- wp:query-pagination-numbers {"className":""} /-->
		<!-- wp:query-pagination-next {"className":""} /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:group -->
