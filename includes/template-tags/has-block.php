<?php
namespace Pulsar;

/**
 * Recursively search a block instance for a specific block, including optional attribute check.
 *
 * @param array  $block The block data to check
 * @param string $block_name The block name to check
 * @param array  $attrs Optional array of attributes to check
 *
 * @return bool
 */
function has_block( $block, $block_name, $attrs = null ): bool {
	if ( $block['blockName'] === $block_name ) {

		// Check if optional attrs are present
		if ( $attrs !== null ) {
			foreach ( $attrs as $key => $value ) {
				if ( ! isset( $block['attrs'][ $key ] ) || $block['attrs'][ $key ] !== $value ) {
					return false;
				}
			}
		}

		return true;
	}

	// Check innerBlocks recursively
	if ( isset( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
		foreach ( $block['innerBlocks'] as $inner_block ) {
			if ( has_block( $inner_block, $block_name, $attrs ) ) {
				return true;
			}
		}
	}

	return false;
}
