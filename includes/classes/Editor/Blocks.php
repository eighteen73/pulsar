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
		add_action( 'init', [ $this, 'register' ] );
		add_filter( 'allowed_block_types_all', [ $this, 'restrict_blocks' ], 10, 2 );
	}

	/**
	 * Registers custom blocks.
	 *
	 * @access public
	 * @return void
	 */
	public function register() {
		global $wp_version;

		$is_pre_wp_6 = version_compare( $wp_version, '6.0', '<' );

		if ( $is_pre_wp_6 ) {
			// Filter the plugins URL to allow us to have blocks in themes with linked assets. i.e editorScripts
			add_filter( 'plugins_url', [ $this, 'filter_plugins_url' ], 10, 2 );
		}

		$blocks_directory = get_theme_file_path( '/blocks/' );

		// Register all the blocks in the theme
		if ( file_exists( $blocks_directory ) ) {
			$block_json_files = glob( $blocks_directory . '*/block.json' );

			// auto register all blocks that were found.
			foreach ( $block_json_files as $filename ) {

				$block_folder = dirname( $filename );

				$block_options = [];

				$markup_file_path = $block_folder . '/markup.php';
				if ( file_exists( $markup_file_path ) ) {

					// only add the render callback if the block has a file called markup.php in it's directory
					$block_options['render_callback'] = function ( $attributes, $content, $block ) use ( $block_folder ) {

						// create helpful variables that will be accessible in markup.php file
						$context = $block->context;

						// get the actual markup from the markup.php file
						ob_start();
						include "{$block_folder}/markup.php";
						return ob_get_clean();
					};
				};

				register_block_type_from_metadata( $block_folder, $block_options );
			};
		};

		if ( $is_pre_wp_6 ) {
			// Remove the filter after we register the blocks
			remove_filter( 'plugins_url', [ $this, 'filter_plugins_url' ], 10, 2 );
		}
	}

	/**
	 * Filter the plugins_url to allow us to use assets from theme.
	 *
	 * @param string $url The plugins url
	 * @param string $path The path to the asset.
	 *
	 * @return string The overridden url to the block asset.
	 */
	public function filter_plugins_url( $url, $path ) {
		$file = preg_replace( '/\.\.\//', '', $path );
		return trailingslashit( get_stylesheet_directory_uri() ) . $file;
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
