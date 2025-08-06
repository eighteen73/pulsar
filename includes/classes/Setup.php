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
	public function boot(): void {
		add_action( 'after_setup_theme', [ $this, 'supports' ], 5 );
		add_action( 'init', [ $this, 'menus' ] );
		add_action( 'init', [ $this, 'image_sizes' ] );
		add_filter( 'image_size_names_choose', [ $this, 'image_size_names' ] );
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
	 * Set up theme support.
	 *
	 * @return void
	 */
	public function supports(): void {

		// Disable core block patterns.
		remove_theme_support( 'core-block-patterns' );
	}

	/**
	 * Register menus.
	 *
	 * @return void
	 */
	public function menus(): void {

		register_nav_menus(
			[
				'primary'  => esc_html_x( 'Primary', 'nav menu location', 'pulsar' ),
				'footer-1' => esc_html_x( 'Footer 1', 'nav menu location', 'pulsar' ),
				'footer-2' => esc_html_x( 'Footer 2', 'nav menu location', 'pulsar' ),
				'legal'    => esc_html_x( 'Legal', 'nav menu location', 'pulsar' ),
			]
		);
	}

	/**
	 * Add custom image sizes.
	 *
	 * @return void
	 */
	public function image_sizes(): void {
		add_image_size( 'extra-large', 1536, 0, false );
	}

	/**
	 * Register custom image size names.
	 *
	 * @param array $sizes Array of image size names.
	 *
	 * @return array
	 */
	public function image_size_names( array $sizes ): array {
		$position = array_search( 'full', array_keys( $sizes ), true );

		$custom_sizes = [
			'extra-large' => __( 'Extra Large', 'pulsar' ),
		];

		$sizes = array_slice( $sizes, 0, $position, true ) +
				$custom_sizes +
				array_slice( $sizes, $position, null, true );

		return $sizes;
	}
}
