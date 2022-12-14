<?php
if ( ! has_nav_menu( 'primary' ) ) {
	return;
}
?>

<nav id="menu-primary" class="menu-primary">
	<button id="menu-toggle" class="" aria-expanded="false" aria-controls="menu-primary-items">
		<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'pulsar' ); ?></span>
	</button>

	<?php
	wp_nav_menu(
		[
			'theme_location' => 'primary',
			'container'      => '',
			'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			'item_spacing'   => 'discard',
			'menu_id'        => 'menu-primary-items',
		]
	);
	?>
</nav>
