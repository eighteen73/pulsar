<?php
/**
 * Block bindings handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;
use WP_Block;

/**
 * Block bindings handling.
 */
class Bindings implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'init', [ $this, 'register_bindings' ] );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return true;
	}


	/**
	 * Registers custom block bindings.
	 *
	 * @access public
	 * @return void
	 */
	public function register_bindings(): void {
		register_block_bindings_source(
			'pulsar/term-featured-image',
			[
				'label'              => __( 'Term Featured Image', 'pulsar' ),
				'get_value_callback' => [ $this, 'term_featured_image' ],
				'uses_context'       => [ 'termId', 'taxonomy' ],
			]
		);
	}

	/**
	 * Gets the value for a term featured image block binding.
	 *
	 * @access public
	 * @param array    $source_args    The source arguments.
	 * @param WP_Block $block_instance The block instance.
	 * @param string   $attribute_name The block attribute being bound.
	 * @return mixed
	 */
	public function term_featured_image( array $source_args, WP_Block $block_instance, string $attribute_name ): mixed {
		if ( 'url' !== $attribute_name ) {
			return null;
		}

		$term_id  = isset( $block_instance->context['termId'] ) ? absint( $block_instance->context['termId'] ) : 0;
		$taxonomy = isset( $block_instance->context['taxonomy'] ) && is_string( $block_instance->context['taxonomy'] )
			? sanitize_key( $block_instance->context['taxonomy'] )
			: '';

		if ( 0 === $term_id || 'product_cat' !== $taxonomy ) {
			return null;
		}

		$category = get_term( $term_id, $taxonomy );

		if ( ! $category || is_wp_error( $category ) ) {
			return null;
		}

		$thumbnail_id = absint( get_term_meta( $category->term_id, 'thumbnail_id', true ) );

		if ( 0 === $thumbnail_id ) {
			return null;
		}

		$image_size = isset( $source_args['size'] ) && is_string( $source_args['size'] ) ? sanitize_key( $source_args['size'] ) : 'full';
		$image_size = '' !== $image_size ? $image_size : 'full';

		$image_url = wp_get_attachment_image_url( $thumbnail_id, $image_size );

		return $image_url ? $image_url : null;
	}
}
