<?php
namespace Pulsar;

/**
 * Check if the post title for the current page should be shown.
 *
 * @return bool
 */
function display_post_title() {
	global $post;

	return get_post_meta( $post->ID, 'display_post_title', true );
}
