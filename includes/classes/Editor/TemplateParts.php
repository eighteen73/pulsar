<?php
/**
 * Template part handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Template part handling.
 */
class TemplateParts implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'rest_api_init', [ $this, 'register_template_parts_route' ] );
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
	 * Register the route for the template parts.
	 *
	 * @return void
	 */
	public function register_template_parts_route(): void {
		register_rest_route(
			'pulsar/v1',
			'/template-parts',
			[
				'methods'  => 'GET',
				'callback' => [ $this, 'get_template_parts' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			]
		);
	}

	/**
	 * Get the template parts.
	 *
	 * @return array
	 */
	public function get_template_parts(): array {
		$templates = [];

		$templates_directory = get_theme_file_path( '/template-parts/' );

		if ( file_exists( $templates_directory ) ) {
			$iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $templates_directory ) );

			foreach ( $iterator as $file ) {
				if ( $file->isFile() && $file->getExtension() === 'php' ) {
					$template_data = $this->get_template_part_data( $file->getPathname() );
					$templates[] = [
						'title' => $template_data['title'],
						'slug'  => $template_data['slug'],
					];
				}
			}
		}

		return $templates;
	}

	/**
	 * Get the template part data.
	 *
	 * @param string $file The file to get the data from.
	 *
	 * @return array
	 */
	public function get_template_part_data( string $file ): array {
		$data = get_file_data(
			$file,
			[
				'title' => 'Title',
				'slug'  => 'Slug',
			]
		);

		return $data;
	}
}
