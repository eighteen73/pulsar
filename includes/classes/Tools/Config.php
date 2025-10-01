<?php
/**
 * Config Class.
 *
 * A simple class for grabbing and returning a configuration file from `/config`.
 *
 * @package Pulsar
 */

namespace Pulsar\Tools;

/**
 * Config class.
 */
class Config {

	/**
	 * Includes and returns a given PHP config file. The file must return
	 * an array.
	 *
	 * @param  string $name The name of the config file.
	 * @return array
	 */
	public static function get( $name ) {

		// Get parent theme config
		$parent_file   = static::parent_path( "{$name}.php" );
		$parent_config = file_exists( $parent_file ) ? include $parent_file : [];

		// Get child theme config (if exists)
		$child_file   = static::child_path( "{$name}.php" );
		$child_config = file_exists( $child_file ) ? include $child_file : [];

		// Handle different merge strategies based on config type and child preferences
		if ( $name === 'bindings' && ! empty( $child_config ) ) {
			// Bindings always use smart merging
			$config = static::merge_bindings( $parent_config, $child_config );
		} elseif ( ! empty( $child_config ) && static::should_merge( $child_file ) ) {
			// Child theme wants to merge with parent (based on file header)
			$config = array_merge( $parent_config, $child_config );
		} elseif ( ! empty( $child_config ) ) {
			// Child theme wants to replace parent (default behavior)
			$config = $child_config;
		} else {
			// No child config, use parent
			$config = $parent_config;
		}

		return (array) apply_filters(
			"pulsar/config/{$name}/",
			$config
		);
	}

	/**
	 * Returns the config path or file path if set.
	 *
	 * @param  string $file The file name.
	 * @return string
	 */
	public static function path( $file = '' ) {

		$file = trim( $file, '/' );

		return get_theme_file_path( $file ? "config/{$file}" : 'config' );
	}

	/**
	 * Returns the parent theme config path or file path if set.
	 *
	 * @param  string $file The file name.
	 * @return string
	 */
	public static function parent_path( $file = '' ) {

		$file = trim( $file, '/' );

		return get_template_directory() . ( $file ? "/config/{$file}" : '/config' );
	}

	/**
	 * Returns the child theme config path or file path if set.
	 *
	 * @param  string $file The file name.
	 * @return string
	 */
	public static function child_path( $file = '' ) {

		$file = trim( $file, '/' );

		return get_stylesheet_directory() . ( $file ? "/config/{$file}" : '/config' );
	}

	/**
	 * Smart merge bindings - child theme classes override parent classes
	 * with the same base name (e.g., Child\Setup overrides Pulsar\Setup).
	 *
	 * @param  array $parent_config Parent theme bindings.
	 * @param  array $child_config  Child theme bindings.
	 * @return array
	 */
	private static function merge_bindings( $parent_config, $child_config ) {

		// Extract base class names from child config
		$child_base_classes = array_map(
			function ( $class_name ) {
				return basename( str_replace( '\\', '/', $class_name ) );
			},
			$child_config
		);

		// Filter out parent classes that are being overridden
		$filtered_parent = array_filter(
			$parent_config,
			function ( $class_name ) use ( $child_base_classes ) {
				$base_class = basename( str_replace( '\\', '/', $class_name ) );
				return ! in_array( $base_class, $child_base_classes, true );
			}
		);

		// Merge filtered parent classes with child classes
		return array_merge( array_values( $filtered_parent ), $child_config );
	}

	/**
	 * Check if child config wants to merge with parent instead of replace.
	 * Uses file header comment like WordPress plugin/theme headers.
	 *
	 * @param  string $child_file Path to child config file.
	 * @return bool
	 */
	private static function should_merge( string $child_file ): bool {
		if ( ! file_exists( $child_file ) ) {
			return false;
		}

		$file_data = get_file_data( $child_file, [ 'merge' => 'Merge' ] );
		return ! empty( $file_data['merge'] ) &&
				strtolower( trim( $file_data['merge'] ) ) === 'true';
	}
}
