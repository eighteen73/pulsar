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
	public function boot() {
		add_action( 'after_setup_theme', [ $this, 'supports' ], 5 );
		add_action( 'init', [ $this, 'menus' ] );
		add_action( 'init', [ $this, 'image_sizes' ] );
		add_action( 'widgets_init', [ $this, 'widget_areas' ] );
		add_action( 'wp_head', [ $this, 'javascript_detected' ], 0 );
		add_action( 'init', [ $this, 'page_title_meta' ] );
	}

	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	public function supports() {

		// Theme translations.
		load_theme_textdomain( 'pulsar', get_parent_theme_file_path( 'languages' ) );

		// Title tag support.
		add_theme_support( 'title-tag' );

		// Featured image support.
		add_theme_support( 'post-thumbnails' );

		// Selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Custom logo support.
		add_theme_support(
			'custom-logo',
			[
				'width'       => 300,
				'height'      => 200,
				'flex-height' => true,
				'flex-width'  => true,
				'header-text' => [
					'app-header__title',
					'app-header__description',
				],
			]
		);

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
	public function menus() {

		register_nav_menus(
			[
				'primary' => esc_html_x( 'Primary', 'nav menu location', 'pulsar' ),
			]
		);
	}

	/**
	 * Add custom image sizes.
	 *
	 * @return void
	 */
	public function image_sizes() {
		// add_image_size( '4x3', 640, 480, true );
	}

	/**
	 * Register widget areas.
	 *
	 * @return void
	 */
	public function widget_areas() {
		$args = [
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		];

		register_sidebar(
			[
				'id'   => 'primary',
				'name' => esc_html_x( 'Primary', 'sidebar', 'pulsar' ),
			] + $args
		);
	}

	/**
	 * Handles JavaScript detection.
	 *
	 * Replaces the `no-js` class with `js` on the root `<html>` element when JavaScript is detected.
	 *
	 * @return void
	 */
	public function javascript_detected() {

		echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
	}

	/**
	 * Register post meta used in the theme for showing/hiding the page title.
	 *
	 * @return void
	 */
	public function page_title_meta() {

		register_post_meta(
			'',
			'display_post_title',
			[
				'show_in_rest'  => true,
				'single'        => true,
				'default'       => true,
				'type'          => 'boolean',
				'auth_callback' => '__return_true',
			]
		);
	}
}
