<?php
/**
 * Block handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Config;
use WP_Block_Editor_Context;
use WP_Block_Type_Registry;

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

		// Load individual block CSS files on demand.
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );

		// Register custom blocks.
		add_action( 'init', [ $this, 'register_custom_blocks' ] );

		// The blocks that are allowed in the editor.
		add_filter( 'allowed_block_types_all', [ $this, 'allowed_blocks' ], 10, 2 );
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

	/**
	 * Limit the blocks that are available to content editors.
	 *
	 * Only applies to editors with a post (the post editor). The Site Editor is
	 * developer-only and keeps every block.
	 *
	 * @param array|bool              $allowed_blocks Array of block type slugs, or boolean to enable/disable all.
	 * @param WP_Block_Editor_Context $editor_context The current block editor context.
	 * @return array|bool
	 */
	public function allowed_blocks( array|bool $allowed_blocks, WP_Block_Editor_Context $editor_context ): array|bool {
		if ( empty( $editor_context->post ) || false === $allowed_blocks ) {
			return $allowed_blocks;
		}

		$config = Config::get( 'allowed-blocks' );

		if ( empty( $config ) ) {
			return $allowed_blocks;
		}

		$allowed = $this->expand_wildcards( $config );

		// Respect restrictions applied earlier by other code.
		if ( is_array( $allowed_blocks ) ) {
			$allowed = array_values( array_intersect( $allowed, $allowed_blocks ) );
		}

		/**
		 * Filters the blocks available to content editors.
		 *
		 * @param string[]                $allowed        Allowed block names.
		 * @param WP_Block_Editor_Context $editor_context The current block editor context.
		 */
		return apply_filters( 'pulsar_allowed_blocks', $allowed, $editor_context );
	}

	/**
	 * Expand `namespace/*` entries into every registered block in that namespace.
	 *
	 * @param string[] $entries Block names and namespace wildcards.
	 * @return string[]
	 */
	private function expand_wildcards( array $entries ): array {
		$registered = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
		$blocks     = [];

		foreach ( $entries as $entry ) {
			if ( ! str_ends_with( $entry, '/*' ) ) {
				$blocks[] = $entry;
				continue;
			}

			$prefix = substr( $entry, 0, -1 );

			foreach ( $registered as $name ) {
				if ( str_starts_with( $name, $prefix ) ) {
					$blocks[] = $name;
				}
			}
		}

		return array_values( array_unique( $blocks ) );
	}
}
