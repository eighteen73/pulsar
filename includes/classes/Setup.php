<?php
/**
 * Theme setup.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;

/**
 * Setup class.
 */
class Setup implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @return void
	 */
	public function boot() : void {
		add_action( 'after_setup_theme', [ $this, 'supports' ], 5 );
		add_action( 'init', [ $this, 'menus' ] );
		add_action( 'init', [ $this, 'image_sizes' ] );
		add_filter( 'image_size_names_choose', [ $this, 'image_size_names' ] );
		add_action( 'wp_head', [ $this, 'javascript_detected' ], 0 );
	}

	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	public function supports() : void {

		// Theme translations.
		load_theme_textdomain( 'pulsar', get_parent_theme_file_path( 'languages' ) );

		// Title tag support.
		add_theme_support( 'title-tag' );

		// Featured image support.
		add_theme_support( 'post-thumbnails' );

		// Selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Outputs HTML5 markup for core features.
		add_theme_support(
			'html5',
			[
				'script',
				'style',
				'comment-list',
				'comment-form',
				'search-form',
				'gallery',
				'caption',
			]
		);

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Let core handle responsive embed wrappers.
		add_theme_support( 'responsive-embeds' );

		// Disable core block patterns.
		remove_theme_support( 'core-block-patterns' );

		// by adding the `theme.json` file block templates automatically get enabled.
		// because the template editor will need additional QA and work to get right
		// the default is to disable this feature.
		remove_theme_support( 'block-templates' );
	}

	/**
	 * Register menus.
	 *
	 * @return void
	 */
	public function menus() : void {

		register_nav_menus(
			[
				'primary' => esc_html_x( 'Primary', 'nav menu location', 'pulsar' ),
				'footer'  => esc_html_x( 'Footer', 'nav menu location', 'pulsar' ),
			]
		);
	}

	/**
	 * Add custom image sizes.
	 *
	 * @return void
	 */
	public function image_sizes() : void {
		// add_image_size( '4x3', 640, 480, true );
	}

	/**
	 * Register custom image size names.
	 *
	 * @param array $sizes Array of image size names.
	 *
	 * @return array
	 */
	public function image_size_names( array $sizes ) : array {
		return array_merge(
			$sizes,
			[
				// 'example' => __( 'Example' ),
			]
		);
	}

	/**
	 * Handles JavaScript detection.
	 *
	 * Replaces the `no-js` class with `js` on the root `<html>` element when JavaScript is detected.
	 *
	 * @return void
	 */
	public function javascript_detected() : void {
		echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
	}
}
