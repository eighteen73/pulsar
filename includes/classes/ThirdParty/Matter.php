<?php
/**
 * Matter integration.
 *
 * Feeds the theme's configuration (settings fields, breakpoints…) into Matter.
 *
 * @package Pulsar
 */

namespace Pulsar\ThirdParty;

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
		add_filter( 'matter_icon_collections', fn() => [ 'pulsar' ] );
		add_filter( 'matter_carousel_slides', [ $this, 'carousel_slides' ] );
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
			'core/image'  => [ 'variant' => 'slides' ],
			'pulsar/card' => [
				'label'   => __( 'Cards', 'pulsar' ),
				'variant' => 'cards',
			],
			'core/query'  => [ 'variant' => 'cards' ],
		];
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return class_exists( \Eighteen73\Matter\Plugin::class );
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
