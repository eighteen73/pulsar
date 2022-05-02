<?php
/**
 * Menu specific class modifications.
 *
 * @package Pulsar
 */

namespace Pulsar\Menu;

use Pulsar\Contracts\Bootable;

/**
 * Menu class.
 */
class Classes implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() {

		add_filter( 'nav_menu_css_class', [ $this, 'item_classes' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'link_classes' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'submenu_classes' ], 10, 3 );
	}

	/**
	 * Adds option 'menu_item_class' to 'wp_nav_menu'.
	 *
	 * @param array $classes  Array of the CSS classes that are applied to the menu item's <li> element.
	 * @param WP_Post $item   The current menu item
	 * @param stdClass        An object of wp_nav_menu() arguments.
	 * @param int             Depth of menu item.
	 *
	 * @return array
	 */
	public function item_classes( array $classes, \WP_Post $item, \stdClass $args, int $depth ) {

		if ( isset( $args->menu_item_class ) ) {
			$classes[] = $args->menu_item_class;
		}

		if ( isset( $args->{"menu_item_class_$depth"} ) ) {
			$classes[] = $args->{"menu_item_class_$depth"};
		}

		return $classes;
	}

	/**
	 * Adds option 'menu_link_class' to 'wp_nav_menu'.
	 *
	 * @param array $atts     Menu attributes.
	 * @param WP_Post $item   The current menu item.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int $depth      Depth of menu item.
	 *
	 * @return array
	 */
	public function link_classes( array $atts, \WP_Post $item, \stdClass $args, int $depth ) {

		if ( isset( $args->menu_link_class ) ) {
			$atts['class'] = $args->menu_link_class;
		}

		if ( isset( $args->{"menu_link_class_$depth"} ) ) {
			$atts['class'] = $args->{"menu_link_class_$depth"};
		}

		return $atts;
	}

	/**
	 * Adds option 'menu_submenu_class' to 'wp_nav_menu'.
	 *
	 * @param array $classes  Array of the CSS classes that are applied to the menu <ul> element.
	 * @param stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int $depth      Holds the nav menu arguments.
	 *
	 * @return array
	 */
	public function submenu_classes( array $classes, \stdClass $args, int $depth ) {

		if ( isset( $args->menu_submenu_class ) ) {
			$classes[] = $args->menu_submenu_class;
		}

		if ( isset( $args->{"menu_submenu_class_$depth"} ) ) {
			$classes[] = $args->{"menu_submenu_class_$depth"};
		}

		return $classes;
	}

}
