<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package orphansTheme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside class="sidebar">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
