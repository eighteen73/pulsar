<?php
/**
 * Checkout header: logo and a link back to the basket, without navigation.
 *
 * Title: Site header (checkout)
 * Slug: site-header-checkout
 *
 * @package Pulsar
 */

?>
<header class="site-header site-header--checkout">
	<div class="site-header__inner">
		<?php get_template_part( 'template-parts/header/logo' ); ?>

		<div class="site-header__actions">
			<?php
			if ( class_exists( 'WooCommerce' ) ) {
				Pulsar\the_block(
					'woocommerce/cart-link',
					[
						'cartIcon' => 'bag-alt',
						'content'  => __( 'Return to Basket', 'pulsar' ),
					]
				);
			}
			?>
		</div>
	</div>
</header>
