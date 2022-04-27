<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Pulsar
 */

if ( ! is_active_sidebar( 'primary' ) ) {
	return;
}
?>

<aside class="sidebar">
	<?php dynamic_sidebar( 'primary' ); ?>
</aside>
