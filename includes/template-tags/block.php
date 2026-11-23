<?php
/**
 * Render blocks from PHP templates.
 *
 * @package Pulsar
 */

namespace Pulsar;

use WP_Block_Type_Registry;

/**
 * Outputs a dynamic block.
 *
 * @see get_block()
 *
 * @param string $name         Block name, e.g. `woocommerce/mini-cart`.
 * @param array  $attrs        Block attributes.
 * @param array  $inner_blocks Inner blocks as `[ name, attrs, inner_blocks ]` tuples.
 * @return void
 */
function the_block( string $name, array $attrs = [], array $inner_blocks = [] ): void {
	echo get_block( $name, $attrs, $inner_blocks ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block output.
}

/**
 * Returns a dynamic block's rendered HTML.
 *
 * Runs the block through core's render pipeline (block supports, render_block
 * filters, post context, on-demand assets and Interactivity API processing),
 * which is safer than building block comment strings for do_blocks().
 *
 *     Pulsar\the_block( 'woocommerce/mini-cart' );
 *     Pulsar\the_block( 'core/search', [ 'label' => 'Search', 'showLabel' => false ] );
 *
 * Only dynamic (server-rendered) blocks are supported: a static block's markup
 * comes from its JavaScript save function, so write that HTML directly instead.
 *
 * @param string $name         Block name.
 * @param array  $attrs        Block attributes.
 * @param array  $inner_blocks Inner blocks as `[ name, attrs, inner_blocks ]` tuples.
 * @return string
 */
function get_block( string $name, array $attrs = [], array $inner_blocks = [] ): string {
	$block_type = WP_Block_Type_Registry::get_instance()->get_registered( $name );

	if ( ! $block_type ) {
		return '';
	}

	if ( ! $block_type->is_dynamic() ) {
		_doing_it_wrong(
			__FUNCTION__,
			sprintf(
				/* translators: %s: block name. */
				esc_html__( '%s is a static block and cannot be rendered from PHP. Write its HTML directly instead.', 'pulsar' ),
				esc_html( $name )
			),
			'6.0.0'
		);

		return '';
	}

	return \render_block( parse_block_tuple( $name, $attrs, $inner_blocks ) );
}

/**
 * Build a parsed block array (as produced by parse_blocks()).
 *
 * @param string $name         Block name.
 * @param array  $attrs        Block attributes.
 * @param array  $inner_blocks Inner blocks as `[ name, attrs, inner_blocks ]` tuples.
 * @return array
 */
function parse_block_tuple( string $name, array $attrs = [], array $inner_blocks = [] ): array {
	$inner_blocks = array_map(
		fn( array $block ) => parse_block_tuple( (string) $block[0], (array) ( $block[1] ?? [] ), (array) ( $block[2] ?? [] ) ),
		$inner_blocks
	);

	return [
		'blockName'    => $name,
		'attrs'        => $attrs,
		'innerBlocks'  => $inner_blocks,
		'innerHTML'    => '',
		'innerContent' => array_fill( 0, count( $inner_blocks ), null ),
	];
}
