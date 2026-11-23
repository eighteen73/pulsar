<?php
/**
 * My Account's header: the page's title and who's signed in, with a way to
 * log out (or, logged out, what the page is for). Rendered in
 * templates/page-my-account.html.
 *
 * Title: My Account header
 *
 * @package Pulsar
 */

if ( ! function_exists( 'wc_get_page_id' ) ) {
	return;
}

$pulsar_user = wp_get_current_user();
?>
<div class="my-account-header">
	<?php // The page's own title: WooCommerce's `the_title` filter would give the section's. ?>
	<h1 class="my-account-header__title"><?php echo esc_html( get_post_field( 'post_title', wc_get_page_id( 'myaccount' ) ) ); ?></h1>

	<?php if ( $pulsar_user->exists() ) : ?>
		<p class="my-account-header__user">
			<?php
			printf(
				/* translators: %s: the customer's name. */
				esc_html__( 'Signed in as %s', 'pulsar' ),
				'<strong>' . esc_html( $pulsar_user->display_name ) . '</strong>'
			);
			?>
			<a class="my-account-header__logout" href="<?php echo esc_url( wc_logout_url() ); ?>"><?php esc_html_e( 'Log out', 'pulsar' ); ?></a>
		</p>
	<?php else : ?>
		<p class="my-account-header__user"><?php esc_html_e( 'Log in to see your orders and manage your details.', 'pulsar' ); ?></p>
	<?php endif; ?>
</div>
