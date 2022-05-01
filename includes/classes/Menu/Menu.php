<?php
/**
 * Menu specific modifications.
 *
 * @package Pulsar
 */

namespace Pulsar\Menu;

use Pulsar\Contracts\Bootable;

/**
 * Menu class.
 */
class Menu implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() {

		add_filter( 'nav_menu_css_class', [ $this, 'listItemClasses' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'submenuClasses' ], 10, 3 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'linkClasses' ], 10, 4 );
	}

	/**
	 * Adds option 'li_class' to 'wp_nav_menu'.
	 *
	 * @param array $classes  Array of the CSS classes that are applied to the menu item's <li> element.
	 * @param WP_Post $item   The current menu item
	 * @param stdClass        An object of wp_nav_menu() arguments.
	 * @param int             Depth of menu item.
	 *
	 * @return array
	 */
	public function listItemClasses( array $classes, \WP_Post $item, \stdClass $args, int $depth ) {

		if ( isset( $args->li_class ) ) {
			$classes[] = $args->li_class;
		}

		if ( isset( $args->{"li_class_$depth"} ) ) {
			$classes[] = $args->{"li_class_$depth"};
		}

		return $classes;
	}

	/**
	 * Adds option 'submenu_class' to 'wp_nav_menu'.
	 *
	 * @param array $classes  Array of the CSS classes that are applied to the menu <ul> element.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int $depth      Holds the nav menu arguments.
	 *
	 * @return array
	 */
	public function submenuClasses( array $classes, \stdClass $args, int $depth ) {

		if ( isset( $args->submenu_class ) ) {
			$classes[] = $args->submenu_class;
		}

		if ( isset( $args->{"submenu_class_$depth"} ) ) {
			$classes[] = $args->{"submenu_class_$depth"};
		}

		return $classes;
	}

	/**
	 * Adds option 'link_class' to 'wp_nav_menu'.
	 *
	 * @param array $atts     Menu attributes.
	 * @param WP_Post $item   The current menu item.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int $depth      Depth of menu item.
	 *
	 * @return array
	 */
	public function linkClasses( array $atts, \WP_Post $item, \stdClass $args, int $depth ) {

		if ( isset( $args->link_class ) ) {
			$atts['class'] = $args->link_class;
		}

		if ( isset( $args->{"link_class_$depth"} ) ) {
			$atts['class'] = $args->{"link_class_$depth"};
		}

		return $atts;
	}
}
