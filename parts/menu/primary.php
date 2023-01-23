<?php
$location = 'primary';

if ( ! has_nav_menu( $location ) ) {
	return;
}
?>

<nav
	x-data="menu({hover: true})"
	id="menu-<?php echo esc_attr( $location ); ?>"
	class="menu-<?php echo esc_attr( $location ); ?>"
	aria-label="<?php echo esc_attr( ucwords( $location ) ); ?> <?php esc_html_e( 'Menu', 'pulsar' ); ?>"
>
	<button
		id="menu-<?php echo esc_attr( $location ); ?>-toggle"
		class="menu-<?php echo esc_attr( $location ); ?>__toggle"
		aria-controls="menu-<?php echo esc_attr( $location ); ?>-items"
		:aria-expanded="showMenu"
		@click.prevent="toggleMenu()"
	>
		<?php Pulsar\display_svg( 'hamburger', [ 'class' => 'menu-' . esc_attr( $location ) . '__toggle-icon hamburger' ] ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Toggle Menu', 'pulsar' ); ?></span>
	</button>

	<?php
	wp_nav_menu(
		[
			'theme_location' => esc_attr( $location ),
			'container'      => '',
			'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			'item_spacing'   => 'discard',
			'menu_id'        => 'menu-' . esc_attr( $location ) . '-items',
			'walker'         => new \Pulsar\Menu\PrimaryWalker()
		]
	);
	?>
</nav>
