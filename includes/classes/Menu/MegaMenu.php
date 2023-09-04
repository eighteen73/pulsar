<?php
/**
 * Mega menu.
 *
 * @package Pulsar
 */

namespace Pulsar\Menu;

use Pulsar\Contracts\Bootable;

/**
 * Mega menu class.
 */
class MegaMenu implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() : void {
		add_action( 'wp_nav_menu_item_custom_fields', [ $this, 'add_fields' ], 10, 2 );
		add_action( 'wp_update_nav_menu_item', [ $this, 'save_fields' ], 10, 3 );
		add_filter( 'nav_menu_css_class', [ $this, 'add_class' ], 10, 4 );
	}

	/**
	 * Add field to a menu item.
	 *
	 * @param int      $item_id  Menu item ID as a numeric string.
	 * @param \WP_Post $item     Menu item data object.
	 * @return void
	 */
	public function add_fields( int $item_id, \WP_Post $item ) : void {

		// Only allow mega menus for top level menu items.
		if ( ! $item->menu_item_parent == 0 ) {
			return;
		}

		$mega_menu = get_post_meta( $item_id, '_menu_item_mega_menu', true );
		?>

		<p class="field-mega-menu description">
			<label class="description">
				<input type="checkbox" name="menu-item-mega-menu[<?php echo esc_attr( $item_id ); ?>]" id="menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>" value="1" <?php checked( $mega_menu, 'yes' ); ?> />
				<?php esc_html_e( 'Display as mega menu', 'pulsar' ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Save custom fields.
	 *
	 * @param int   $menu_id          ID of the updated menu.
	 * @param int   $menu_item_db_id  ID of the updated menu item.
	 * @param array $args             An array of arguments used to update a menu item.
	 * @return void
	 */
	public function save_fields( int $menu_id, int $menu_item_db_id, array $args ) : void {
		$mega_menu = isset( $_POST['menu-item-mega-menu'][ $menu_item_db_id ] ) ? 'yes' : 'no';

		update_post_meta( $menu_item_db_id, '_menu_item_mega_menu', $mega_menu );
	}

	/**
	 * Modify the menu item classes to add a mega menu class.
	 *
	 * @param array     $classes  Array of the CSS classes that are applied to the menu item's <li> element.
	 * @param \WP_Post  $item     The current menu item
	 * @param \stdClass $args     An object of wp_nav_menu() arguments.
	 * @param int       $depth    Depth of menu item.
	 *
	 * @return array
	 */
	public function add_class( array $classes, \WP_Post $item, \stdClass $args, int $depth ) : array {

		// Use the theme location as a namespace.
		$location = $args->theme_location ? $args->theme_location : 'default';

		// Get the mega menu option.
		$mega_menu = get_post_meta( $item->ID, '_menu_item_mega_menu', true );

		// Set mega menu class if enabled.
		if ( $mega_menu === 'yes' ) {
			$classes[] = 'has-mega-menu';
		}

		return $classes;
	}
}
