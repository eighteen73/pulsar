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


	// Quick dev. toggle to re-enable all blocks while testing.
	const ENABLE_ALL_BLOCKS = true;

	// Should all Pulsar registered blocks be included without having to list them.
	const ENABLE_ALL_PULSAR_BLOCKS = true;

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {

		// Load individual block CSS files on demand.
		add_filter( 'should_load_separate_core_block_assets', '__return_true' );

		// Remove the default SVG filters from the body.
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

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
	 * Limit the blocks that are allowed to content editors.
	 *
	 * @param array|bool               $block_editor_context Array of block type slugs, or boolean to enable/disable all
	 * @param \WP_Block_Editor_Context $editor_context       The current block editor context
	 * @return array|bool
	 */
	public function allowed_blocks( array|bool $block_editor_context, \WP_Block_Editor_Context $editor_context ): array|bool {
		if ( self::ENABLE_ALL_BLOCKS || empty( $editor_context->post ) ) {
			return $block_editor_context;
		}

		if ( self::ENABLE_ALL_PULSAR_BLOCKS ) {
			$registry      = WP_Block_Type_Registry::get_instance();
			$block_names   = array_keys( $registry->get_all_registered() );
			$pulsar_blocks = array_filter(
				$block_names,
				function ( $key ) {
					return strpos( $key, 'pulsar/' ) !== false;
				},
			);

			// Merge the pulsar_blocks array with the allowed-blocks array
			return array_merge( Config::get( 'allowed-blocks' ) ?? [], $pulsar_blocks );
		}

		return Config::get( 'allowed-blocks' ) ?? $block_editor_context;
	}
}
