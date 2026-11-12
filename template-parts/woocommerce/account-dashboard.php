<?php
/**
 * My Account's dashboard: an overview of the customer's recent orders, their
 * addresses and details, each with a way to act on it. Printed on
 * WooCommerce's `woocommerce_account_dashboard` hook (ThirdParty\WooCommerce\Account),
 * so extensions' dashboard content (e.g. subscriptions) follows it.
 *
 * @package Pulsar
 */

$pulsar_customer = get_current_user_id();

if ( ! $pulsar_customer ) {
	return;
}

$pulsar_orders = wc_get_orders(
	[
		'customer' => $pulsar_customer,
		'limit'    => 3,
		'orderby'  => 'date',
		'order'    => 'DESC',
		'status'   => array_keys( wc_get_order_statuses() ),
	]
);

$pulsar_addresses = [ 'billing' => __( 'Billing address', 'pulsar' ) ];

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$pulsar_addresses['shipping'] = __( 'Shipping address', 'pulsar' );
}

$pulsar_user = wp_get_current_user();
?>
<div class="account-overview">
	<section class="account-overview__panel account-overview__panel--orders" aria-labelledby="account-overview-orders">
		<div class="account-overview__header">
			<h3 id="account-overview-orders" class="account-overview__title"><?php esc_html_e( 'Recent orders', 'pulsar' ); ?></h3>
			<?php if ( $pulsar_orders ) : ?>
				<a class="account-overview__more" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'All orders', 'pulsar' ); ?></a>
			<?php endif; ?>
		</div>

		<?php if ( $pulsar_orders ) : ?>
			<ul class="account-orders">
				<?php
				foreach ( $pulsar_orders as $pulsar_order ) :
					$pulsar_status = $pulsar_order->get_status();
					?>
					<li class="account-orders__item">
						<div class="account-orders__summary">
							<a class="account-orders__number" href="<?php echo esc_url( $pulsar_order->get_view_order_url() ); ?>">
								<?php
								/* translators: %s: order number. */
								echo esc_html( sprintf( __( 'Order #%s', 'pulsar' ), $pulsar_order->get_order_number() ) );
								?>
							</a>
							<span class="account-orders__meta">
								<time datetime="<?php echo esc_attr( $pulsar_order->get_date_created()?->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $pulsar_order->get_date_created() ) ); ?></time>
								&middot; <?php echo wp_kses_post( $pulsar_order->get_formatted_order_total() ); ?>
							</span>
						</div>

						<span class="order-status order-status--<?php echo esc_attr( $pulsar_status ); ?>"><?php echo esc_html( wc_get_order_status_name( $pulsar_status ) ); ?></span>

						<div class="account-orders__actions">
							<?php foreach ( wc_get_account_orders_actions( $pulsar_order ) as $pulsar_key => $pulsar_action ) : ?>
								<a href="<?php echo esc_url( $pulsar_action['url'] ); ?>" class="wp-element-button account-orders__action account-orders__action--<?php echo esc_attr( $pulsar_key ); ?>"><?php echo esc_html( $pulsar_action['name'] ); ?></a>
							<?php endforeach; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p class="account-overview__empty"><?php esc_html_e( 'No orders yet.', 'pulsar' ); ?></p>
			<a class="wp-element-button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Start shopping', 'pulsar' ); ?></a>
		<?php endif; ?>
	</section>

	<?php foreach ( $pulsar_addresses as $pulsar_type => $pulsar_label ) : ?>
		<section class="account-overview__panel" aria-labelledby="account-overview-<?php echo esc_attr( $pulsar_type ); ?>">
			<div class="account-overview__header">
				<h3 id="account-overview-<?php echo esc_attr( $pulsar_type ); ?>" class="account-overview__title"><?php echo esc_html( $pulsar_label ); ?></h3>
				<a class="account-overview__more" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $pulsar_type, wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Edit', 'pulsar' ); ?></a>
			</div>

			<?php $pulsar_address = wc_get_account_formatted_address( $pulsar_type ); ?>
			<?php if ( $pulsar_address ) : ?>
				<address><?php echo wp_kses_post( $pulsar_address ); ?></address>
			<?php else : ?>
				<p class="account-overview__empty"><?php esc_html_e( 'Not set up yet.', 'pulsar' ); ?></p>
			<?php endif; ?>
		</section>
	<?php endforeach; ?>

	<section class="account-overview__panel" aria-labelledby="account-overview-details">
		<div class="account-overview__header">
			<h3 id="account-overview-details" class="account-overview__title"><?php esc_html_e( 'Account details', 'pulsar' ); ?></h3>
			<a class="account-overview__more" href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'Edit', 'pulsar' ); ?></a>
		</div>

		<p>
			<?php echo esc_html( trim( $pulsar_user->first_name . ' ' . $pulsar_user->last_name ) ?: $pulsar_user->display_name ); ?><br>
			<?php echo esc_html( $pulsar_user->user_email ); ?>
		</p>
	</section>
</div>
