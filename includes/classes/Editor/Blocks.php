<?php
/**
 * Block handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;

/**
 * Block handling.
 */
class Blocks implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {

		// Register custom blocks.
		add_action( 'init', [ $this, 'register_custom_blocks' ] );
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
	 * Registers custom blocks.
	 *
	 * @access public
	 * @return void
	 */
	public function register_custom_blocks(): void {
		wp_register_block_types_from_metadata_collection(
			get_theme_file_path( 'build/blocks' ),
			get_theme_file_path( 'build/blocks-manifest.php' ),
		);
	}
}
