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
		add_action( 'init', [ $this, 'register' ] );
		add_filter( 'allowed_block_types_all', [ $this, 'restrict_blocks' ], 10, 2 );
		add_action( 'wp_default_styles', [ $this, 'remove_block_styles' ], 10 );
	}

	/**
	 * Registers custom blocks.
	 *
	 * @access public
	 * @return void
	 */
	public function register() {

		$blocks_directory = get_theme_file_path( '/dist/blocks/' );

		// Register all the blocks in the theme
		if ( file_exists( $blocks_directory ) ) {
			$block_json_files = glob( $blocks_directory . '*/block.json' );

			// auto register all blocks that were found.
			foreach ( $block_json_files as $filename ) {

				$block_folder = dirname( $filename );

				$block_options = [];

				$template_file_path = $block_folder . '/template.php';
				if ( file_exists( $template_file_path ) ) {

					// only add the render callback if the block has a file called template.php in it's directory
					$block_options['render_callback'] = function ( $attributes, $content, $block ) use ( $block_folder ) {

						// get the actual markup from the template.php file
						ob_start();
						include "{$block_folder}/template.php";
						return ob_get_clean();
					};
				};

				register_block_type( $block_folder, $block_options );
			};
		};
	}

	/**
	 * Limit the blocks that are made available to content editors
	 *
	 * @param bool|string[]           $block_editor_context Array of block type slugs, or boolean to enable/disable all
	 * @param WP_Block_Editor_Context $editor_context The current block editor context
	 * @return array
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

	/**
	 * Remove block styles from the editor and the front end.
	 *
	 * @return void
	 */
	public function remove_block_styles( $styles ) {

		/**
		 * The stylesheets we want to remove.
		 */
		$handles = [ 'wp-block-library', 'wp-block-library-theme' ];

		foreach ( $handles as $handle ) {

			$style = $styles->query( $handle, 'registered' );
			if ( ! $style ) {
				continue;
			}

			// Remove the style
			$styles->remove( $handle );

			// Remove path and dependencies
			$styles->add( $handle, false, [] );
		}
	}
}
