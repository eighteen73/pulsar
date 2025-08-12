<?php
/**
 * Asset management.
 *
 * @package Pulsar
 */

namespace Pulsar\Tools;

/**
 * Asset class.
 */
class Asset {

	/**
	 * Retrieve the attribute for a matching asset file.
	 *
	 * @param string $slug The slug of the asset file, eg `app-styles`.
	 * @param string $path The path of the asset file, relative to the `dist` directory.
	 * @param string $attribute The attribute to get from the asset file. Typically `version` or `dependencies`.
	 *
	 * @return string|array
	 */
	public static function attribute( string $slug, string $path, string $attribute ): string|array {
		$asset      = self::get( $slug, $path );
		$attributes = [];

		if ( ! empty( $attribute ) && isset( $asset[ $attribute ] ) ) {
			return $asset[ $attribute ];
		}

		if ( $attribute === 'dependencies' && SCRIPT_DEBUG === true ) {
			$attributes[] = 'wp-react-refresh-runtime';
		}

		return $attributes;
	}

	/**
	 * Retrieve the matching asset file.
	 *
	 * @param string $slug The slug of the asset file, eg `app-styles`.
	 * @param string $path The path of the asset file, relative to the `dist` directory.
	 *
	 * @return array|null
	 */
	public static function get( string $slug, string $path ): array|null {
		$asset = null;

		// Try child theme first, then fall back to parent theme
		$asset_file = "build/{$path}/{$slug}.asset.php";

		if ( file_exists( get_theme_file_path( $asset_file ) ) ) {
			$asset = require get_theme_file_path( $asset_file );
		}

		return $asset;
	}
}
