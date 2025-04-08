<?php
/**
 * Bootstrap all of the theme classes.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Tools\Config;

/**
 * Mini container.  This allows us to set up single instances of our objects
 * without using the singleton pattern and gives third-party devs easy access to
 * the objects if they need to unhook actions/filters added by the classes.
 *
 * Child theme authors can access the objects via `theme( $class_name )`.
 *
 * @access public
 * @param  string $class_name The class name
 * @return mixed
 */
function theme( string $class_name = '' ) {
	static $classes = null;

	// On first run, create new components and boot them.
	if ( is_null( $classes ) ) {
		$bindings = Config::get( 'bindings' );

		foreach ( $bindings as $binding ) {
			$classes[ $binding ] = new $binding();

			if ( $classes[ $binding ]->can_boot() ) {
				$classes[ $binding ]->boot();
			}
		}
	}

	return $class_name ? $classes[ $class_name ] : $classes;
}

/**
 * Bootstrap theme.
 * Run a small bootstrapping routine.
 */
do_action( 'pulsar/booted', theme() );
