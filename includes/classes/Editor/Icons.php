<?php
/**
 * Register theme SVG icons with the WordPress 7.1 icon library.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;

/**
 * Icon collection registrar.
 */
class Icons implements Bootable {

	/**
	 * Collection slug.
	 *
	 * @var string
	 */
	public const COLLECTION = 'pulsar';

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @access public
	 * @return bool
	 */
	public function can_boot(): bool {
		return function_exists( 'wp_register_icon_collection' ) && function_exists( 'wp_register_icon' );
	}

	/**
	 * Register the collection and SVG files from icon directories.
	 *
	 * @access public
	 * @return void
	 */
	public function register(): void {
		$files = $this->get_icon_files();

		if ( empty( $files ) ) {
			return;
		}

		wp_register_icon_collection(
			self::COLLECTION,
			[
				'label'       => get_bloginfo( 'name' ) ?: __( 'Pulsar', 'pulsar' ),
				'description' => __( 'Theme icons.', 'pulsar' ),
			]
		);

		foreach ( $files as $slug => $file ) {
			wp_register_icon(
				self::COLLECTION . '/' . $slug,
				[
					'label'     => $this->get_icon_label( $slug ),
					'file_path' => $file,
				]
			);
		}
	}

	/**
	 * Glob SVG files from icon directories, keyed by slug.
	 *
	 * Later directories override earlier ones with the same slug.
	 *
	 * @access public
	 * @return array<string, string> Slug => file path.
	 */
	public function get_icon_files(): array {
		$files = [];

		foreach ( $this->get_directories() as $directory ) {
			$matches = glob( trailingslashit( $directory ) . '*.svg' );

			if ( ! is_array( $matches ) ) {
				continue;
			}

			foreach ( $matches as $file ) {
				$slug = $this->get_icon_slug( $file );

				if ( $slug ) {
					$files[ $slug ] = $file;
				}
			}
		}

		return $files;
	}

	/**
	 * Directories to load SVG icons from.
	 *
	 * Parent theme first, then the child theme so it can override slugs.
	 * Filterable via `pulsar_icon_directories`.
	 *
	 * @access public
	 * @return string[]
	 */
	public function get_directories(): array {
		$directories = [];
		$parent      = get_template_directory() . '/assets/svg/icons';

		if ( is_dir( $parent ) ) {
			$directories[] = $parent;
		}

		if ( is_child_theme() ) {
			$child = get_stylesheet_directory() . '/assets/svg/icons';

			if ( is_dir( $child ) && $child !== $parent ) {
				$directories[] = $child;
			}
		}

		return apply_filters( 'pulsar_icon_directories', $directories );
	}

	/**
	 * Slug from an SVG filename, or empty when it is not a valid icon name.
	 *
	 * @param string $file Absolute file path.
	 * @return string
	 */
	private function get_icon_slug( string $file ): string {
		$slug = sanitize_title( basename( $file, '.svg' ) );

		if ( ! preg_match( '/^[a-z0-9](?:[a-z0-9_-]*[a-z0-9])?$/', $slug ) ) {
			return '';
		}

		return $slug;
	}

	/**
	 * Human-readable label for an icon slug.
	 *
	 * @param string $slug Icon slug.
	 * @return string
	 */
	private function get_icon_label( string $slug ): string {
		return ucwords( str_replace( [ '-', '_' ], ' ', $slug ) );
	}
}
