<?php
/**
 * Renders a PHP template part from the theme's template-parts folder.
 *
 * @param array    $attributes The array of attributes for this block.
 * @param string   $content    Rendered block output.
 * @param WP_Block $block      The instance of the WP_Block class that represents the block being rendered.
 *
 * @package Pulsar
 */

$pulsar_slug = (string) ( $attributes['slug'] ?? '' );

// Only plain path segments: no dots, so a slug can't leave template-parts/.
if ( ! preg_match( '#^[a-z0-9_-]+(?:/[a-z0-9_-]+)*$#i', $pulsar_slug ) ) {
	return;
}

// Pass block context (postId, postType, queryId) through to the template.
$pulsar_args = $block->context ? array_merge( $attributes, $block->context ) : $attributes;

get_template_part( "template-parts/{$pulsar_slug}", null, $pulsar_args );
