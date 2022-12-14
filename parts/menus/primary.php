<?php
$location = 'primary';

if ( ! has_nav_menu( $location ) ) {
	return;
}
?>

<nav id="menu-<?php echo esc_attr( $location ); ?>" class="menu-<?php echo esc_attr( $location ); ?>">
	<button id="menu-<?php echo esc_attr( $location ); ?>-toggle" class="menu-<?php echo esc_attr( $location ); ?>__toggle" aria-expanded="false" aria-controls="menu-<?php echo esc_attr( $location ); ?>-items">
		<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'pulsar' ); ?></span>
	</button>

	<?php
	wp_nav_menu(
		[
			'theme_location' => esc_attr( $location ),
			'container'      => '',
			'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			'item_spacing'   => 'discard',
			'menu_id'        => 'menu-' . esc_attr( $location ) . '-items',
		]
	);
	?>
</nav>
