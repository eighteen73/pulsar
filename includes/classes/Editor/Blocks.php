<?php
/**
 * Block handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Config;

/**
 * Block handling.
 */
class Blocks implements Bootable {


	// Quick dev. toggle to re-enable all blocks while testing
	const ENABLE_ALL_BLOCKS = false;

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() {
		add_filter( 'allowed_block_types_all', [ $this, 'restrict_blocks' ], 10, 2 );
	}

	/**
	 * Limit the blocks that are made available to content editors
	 */
	public function restrict_blocks( $block_editor_context, $editor_context ) {
		if ( self::ENABLE_ALL_BLOCKS || empty( $editor_context->post ) ) {
			return $block_editor_context;
		}

		$blocks = Config::get( 'blocks' );
		if ( ! $blocks ) {
			return $block_editor_context;
		}

		return $blocks;
	}
}
