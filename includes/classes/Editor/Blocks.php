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


	// Quick dev. toggle to re-enable all blocks while testing.
	const ENABLE_ALL_BLOCKS = false;

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() {

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
	 * Registers custom blocks.
	 *
	 * @access public
	 * @return void
	 */
	public function register_custom_blocks() {

		$blocks_directory = get_theme_file_path( '/dist/blocks/' );

		// Register all the blocks in the theme.
		if ( file_exists( $blocks_directory ) ) {
			$block_json_files = glob( $blocks_directory . '*/block.json' );

			// auto register all blocks that were found.
			foreach ( $block_json_files as $filename ) {
				$block_folder = dirname( $filename );
				register_block_type( $block_folder );
			};
		};
	}

	/**
	 * Limit the blocks that are allowed to content editors.
	 *
	 * @param bool|string[]           $block_editor_context Array of block type slugs, or boolean to enable/disable all
	 * @param WP_Block_Editor_Context $editor_context The current block editor context
	 * @return array
	 */
	public function allowed_blocks( $block_editor_context, $editor_context ) {
		if ( self::ENABLE_ALL_BLOCKS || empty( $editor_context->post ) ) {
			return $block_editor_context;
		}

		$blocks = Config::get( 'allowed-blocks' );
		if ( ! $blocks ) {
			return $block_editor_context;
		}

		return $blocks;
	}
}
