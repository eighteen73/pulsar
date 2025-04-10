<?php
/**
 * Theme assets enqueue.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Asset;
use Pulsar\Tools\Config;

/**
 * Enqueue scripts, styles and fonts.
 */
class Enqueue implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'theme_styles' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'theme_scripts' ], 10 );
		add_action( 'after_setup_theme', [ $this, 'block_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'disabled_block_styles' ], 999 );
		add_action( 'after_setup_theme', [ $this, 'editor_styles' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 10 );
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
	 * Theme stylesheets.
	 *
	 * @return void
	 */
	public function theme_styles(): void {

		wp_enqueue_style(
			'pulsar-app-styles',
			get_theme_file_uri( 'build/css/app.css' ),
			[],
			Asset::attribute( 'app', 'css', 'version' ),
			'screen, print',
		);
	}

	/**
	 * Theme scripts.
	 *
	 * @return void
	 */
	public function theme_scripts(): void {

		wp_enqueue_script(
			'pulsar-app-scripts',
			get_theme_file_uri( 'build/js/app.js' ),
			Asset::attribute( 'app', 'js', 'dependencies' ),
			Asset::attribute( 'app', 'js', 'version' ),
			[
				'in_footer' => true,
				'strategy'  => 'defer',
			],
		);
	}

	/**
	 * Enqueues block-specific stylesheets so that they only load when the block
	 * is in use. Block styles are stored under `/assets/css/blocks` are
	 * automatically enqueued. Each file should be named
	 * `{$block_namespace}-{$block_slug}.css`.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/wp_enqueue_block_style/
	 *
	 * @return void
	 */
	public function block_styles(): void {

		// Gets all the block stylesheets.
		$files = glob( get_theme_file_path( 'build/css/blocks/*.css' ) );

		foreach ( $files as $file ) {

			// Gets the filename without the path or extension.
			$name = str_replace(
				[
					get_theme_file_path( 'build/css/blocks/' ),
					'.css',
				],
				'',
				$file
			);

			// Sanitize the name to make sure it contains only
			// characters allowed in a block type name.
			$name = preg_replace( '/[^a-z0-9-]/', '', strtolower( $name ) );

			// Get the position of the first hyphen.
			$pos = strpos( $name, '-' );

			// Bail if there is no hyphen.
			if ( false === $pos ) {
				continue;
			}

			// Converts the filename to its associated block name by
			// replacing the first `-` with a `/`. Filenames must
			// use `{namespace}-{slug}` for this to work.
			$block = substr_replace( $name, '/', $pos, 1 );

			// Register the block style.
			wp_enqueue_block_style(
				$block,
				[
					'handle' => "pulsar-block-{$name}",
					'src'    => get_theme_file_uri( "build/css/blocks/{$name}.css" ),
					'path'   => get_theme_file_path( "build/css/blocks/{$name}.css" ),
					'deps'   => Asset::attribute( $name, 'css/blocks', 'dependencies' ),
					'ver'    => Asset::attribute( $name, 'css/blocks', 'version' ),
					'media'  => 'screen, print',
				],
			);
		}
	}

	/**
	 * Disable individual block stylesheets.
	 *
	 * @return void
	 */
	public function disabled_block_styles(): void {

		$blocks = Config::get( 'disabled-block-styles' );

		if ( ! $blocks ) {
			return;
		}

		foreach ( $blocks as $block ) {
			wp_dequeue_style( "wp-block-{$block}" );
		}
	}

	/**
	 * Editor stylesheets.
	 *
	 * @return void
	 */
	public function editor_styles(): void {

		add_editor_style(
			[
				'build/css/editor.css',
			]
		);
	}

	/**
	 * Editor scripts.
	 *
	 * @return void
	 */
	public function editor_scripts(): void {

		wp_enqueue_script(
			'pulsar-editor-scripts',
			get_theme_file_uri( 'build/js/editor.js' ),
			Asset::attribute( 'editor-scripts', 'js', 'dependencies' ),
			Asset::attribute( 'editor-scripts', 'js', 'version' ),
			[
				'in_footer' => true,
			],
		);
	}

	/**
	 * Admin stylesheets.
	 *
	 * @return void
	 */
	public function admin_styles(): void {}

	/**
	 * Admin scripts.
	 *
	 * @return void
	 */
	public function admin_scripts(): void {}
}
