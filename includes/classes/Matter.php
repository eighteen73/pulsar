<?php
/**
 * Matter integration.
 *
 * Feeds the theme's configuration (settings fields, breakpoints…) into Matter.
 * Matter is a must-use plugin, so it's always loaded and the theme uses it
 * without checking.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Config;

/**
 * Matter integration.
 */
class Matter implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_filter( 'matter_settings_fields', [ $this, 'settings_fields' ] );
		add_filter( 'matter_breakpoints', [ $this, 'breakpoints' ] );
		add_filter( 'matter_variants', [ $this, 'variants' ] );
		add_filter( 'matter_allowed_blocks', [ $this, 'allowed_blocks' ] );
		add_filter( 'matter_theme_icons', [ $this, 'icons' ] );
		add_filter( 'matter_icon_collections', fn() => [ 'pulsar' ] );
		add_filter( 'matter_carousel_slides', [ $this, 'carousel_slides' ] );
		add_filter( 'matter_archive_default_content', [ $this, 'archive_default_content' ], 10, 2 );
	}

	/**
	 * Archives without their own content show (and editors customising one
	 * start from) a pattern: patterns/archive-404.php, archive-search.php,
	 * or archive.php for the rest. Add e.g. `pulsar/archive-product` to give
	 * an archive type its own.
	 *
	 * @param string                             $content Matter's default.
	 * @param \Eighteen73\Matter\Archives\Target $target  The archive.
	 * @return string
	 */
	public function archive_default_content( string $content, $target ): string {
		$registry = \WP_Block_Patterns_Registry::get_instance();
		$slugs    = match ( $target->type ) {
			'404', 'search' => [ "pulsar/archive-{$target->type}" ],
			default         => [ 'pulsar/archive-' . $target->post_type(), 'pulsar/archive' ],
		};

		foreach ( $slugs as $slug ) {
			if ( $registry->is_registered( $slug ) ) {
				return (string) ( $registry->get_registered( $slug )['content'] ?? $content );
			}
		}

		return $content;
	}

	/**
	 * What a new carousel can hold, with the variant (config/matter.php) it
	 * starts as. Matter offers images, posts and products; cards are added.
	 * A slide type's block needs `matter/carousel` in its block.json `parent`.
	 *
	 * @return array
	 */
	public function carousel_slides(): array {
		return [
			'core/image'                     => [ 'variant' => 'slides' ],
			'pulsar/card'                    => [
				'label'   => __( 'Cards', 'pulsar' ),
				'variant' => 'cards',
			],
			'core/query'                     => [ 'variant' => 'cards' ],
			'woocommerce/product-collection' => [ 'variant' => 'cards' ],
		];
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
	 * The blocks content editors can add, from `config/allowed-blocks.php`.
	 *
	 * @param string[] $blocks Blocks allowed elsewhere.
	 * @return string[]
	 */
	public function allowed_blocks( array $blocks ): array {
		return array_merge( $blocks, Config::get( 'allowed-blocks' ) );
	}

	/**
	 * The theme's icons (`assets/svg/icons/`) are the `pulsar` icon
	 * collection. Named here rather than after the theme's folder, because
	 * icon names (`pulsar/arrow-right`) are saved in content.
	 *
	 * @param array $icons Matter's defaults.
	 * @return array
	 */
	public function icons( array $icons ): array {
		return array_merge(
			$icons,
			[
				'collection' => 'pulsar',
				'label'      => __( 'Theme icons', 'pulsar' ),
			]
		);
	}

	/**
	 * Site settings fields from `config/settings.php`.
	 *
	 * @param array $fields Fields declared elsewhere.
	 * @return array
	 */
	public function settings_fields( array $fields ): array {
		return array_merge( $fields, Config::get( 'settings' ) );
	}

	/**
	 * Breakpoints from `config/breakpoints.json`, the theme's single source
	 * (also compiled into the SCSS breakpoint map).
	 *
	 * @param array<string, string> $breakpoints Matter's defaults.
	 * @return array<string, string>
	 */
	public function breakpoints( array $breakpoints ): array {
		$theme = wp_json_file_decode( get_theme_file_path( 'config/breakpoints.json' ), [ 'associative' => true ] );

		return is_array( $theme ) ? $theme : $breakpoints;
	}

	/**
	 * Block variants from `config/matter.php`, merged over Matter's defaults.
	 *
	 * @param array $variants Matter's variants.
	 * @return array
	 */
	public function variants( array $variants ): array {
		return array_replace_recursive( $variants, Config::get( 'matter' ) );
	}
}
