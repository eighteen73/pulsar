<?php
/**
 * All of the parameters passed to the function where this file is being required are accessible in this scope:
 *
 * @param array    $attributes   The array of attributes for this block.
 * @param string   $content      Rendered block output. ie. <InnerBlocks.Content />.
 * @param WP_Block $block        The instance of the WP_Block class that represents the block being rendered.
 *
 * @package Pulsar
 */

$slug = $attributes['slug'] ?? false;
$name = $attributes['name'] ?? null;

// We need a slug to be able to render the template.
if ( ! $slug ) {
	return;
}

// Add context to args if available.
$args = $block->context ? array_merge( $attributes, $block->context ) : $attributes;

// Render the template.
get_template_part( "template-parts/{$slug}", $name, $args );
