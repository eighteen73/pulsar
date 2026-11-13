<?php
/**
 * Content for the extension browser tests (tests/e2e/extensions.spec.js),
 * when the extensions are mapped into wp-env (see
 * extensions.override.example.json). Each part only runs with its plugin
 * active. Idempotent: everything is found by slug, email or ID first, and
 * the products' slugs start with pulsar-ext- so content.js leaves them be.
 *
 * Run by tests/e2e/global-setup.js:
 * wp-env run tests-cli wp eval-file wp-content/themes/pulsar/tests/wp-env/seed-extensions.php
 *
 * @package Pulsar
 */

// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// No order or account emails (there's no mail server).
add_filter( 'pre_wp_mail', '__return_false' );

$out = [];

/**
 * A published product by slug, created if missing.
 *
 * @param string   $slug  Slug.
 * @param string   $class Product class.
 * @param callable $setup Sets it up.
 * @return WC_Product
 */
$product = function ( string $slug, string $class, callable $setup ): WC_Product {
	$existing = get_page_by_path( $slug, OBJECT, 'product' );

	if ( $existing ) {
		return wc_get_product( $existing->ID );
	}

	$product = new $class();
	$product->set_slug( $slug );
	$product->set_status( 'publish' );
	$setup( $product );
	$product->save();

	return $product;
};

// The customer.
$email = 'pulsar.ext@example.com';
$user  = get_user_by( 'email', $email );

if ( ! $user ) {
	$user = get_user_by( 'id', wc_create_new_customer( $email, 'pulsarext', 'Pulsar-ext-1' ) );
}

$customer = new WC_Customer( $user->ID );
$address  = [
	'first_name' => 'Pat',
	'last_name'  => 'Customer',
	'address_1'  => '1 Test Street',
	'city'       => 'Leeds',
	'postcode'   => 'LS1 1AA',
	'country'    => 'GB',
];

foreach ( $address as $key => $value ) {
	$customer->{"set_billing_$key"}( $value );
	$customer->{"set_shipping_$key"}( $value );
}

$customer->set_first_name( 'Pat' );
$customer->set_last_name( 'Customer' );
$customer->set_billing_email( $email );
$customer->save();

/**
 * An order for the customer, made once (found by its meta key).
 *
 * @param string   $key   Meta key that marks it.
 * @param callable $setup Adds its items; returns nothing.
 * @return WC_Order|null The new order, or null when it was made before.
 */
$order_once = function ( string $key, callable $setup ) use ( $user, $customer ): ?WC_Order {
	if ( wc_get_orders( [ 'customer' => $user->ID, 'limit' => 1, 'meta_key' => $key ] ) ) { // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		return null;
	}

	$order = wc_create_order( [ 'customer_id' => $user->ID ] );
	$setup( $order );
	$order->set_address( $customer->get_billing(), 'billing' );
	$order->set_address( $customer->get_shipping(), 'shipping' );
	$order->set_payment_method( 'bacs' );
	$order->update_meta_data( $key, 1 );
	$order->calculate_totals();
	$order->save();

	return $order;
};

$tee = $product(
	'pulsar-ext-tee',
	WC_Product_Simple::class,
	function ( $p ) {
		$p->set_name( 'Ext tee' );
		$p->set_regular_price( '20' );
	}
);
$mug = $product(
	'pulsar-ext-mug',
	WC_Product_Simple::class,
	function ( $p ) {
		$p->set_name( 'Ext mug' );
		$p->set_regular_price( '8' );
	}
);

// All Products for Subscriptions: buy once, or subscribe monthly or every
// two months.
if ( class_exists( 'WCS_ATT' ) ) {
	$product(
		'pulsar-ext-coffee',
		WC_Product_Simple::class,
		function ( $p ) {
			$p->set_name( 'Ext coffee' );
			$p->set_regular_price( '12' );
			$p->update_meta_data(
				'_wcsatt_schemes',
				[
					[
						'subscription_period_interval' => 1,
						'subscription_period'          => 'month',
						'subscription_length'          => 0,
						'subscription_pricing_method'  => 'inherit',
						'subscription_discount'        => '10',
					],
					[
						'subscription_period_interval' => 2,
						'subscription_period'          => 'month',
						'subscription_length'          => 0,
						'subscription_pricing_method'  => 'inherit',
						'subscription_discount'        => '5',
					],
				]
			);
		}
	);
	$out[] = 'all products for subscriptions';
}

// Subscriptions: a monthly box, and the customer subscribed to it.
if ( class_exists( 'WC_Subscriptions' ) && function_exists( 'wcs_create_subscription' ) ) {
	$box = $product(
		'pulsar-ext-box',
		WC_Product_Subscription::class,
		function ( $p ) {
			$p->set_name( 'Ext monthly box' );
			$p->set_regular_price( '25' );
			$p->update_meta_data( '_subscription_price', '25' );
			$p->update_meta_data( '_subscription_period', 'month' );
			$p->update_meta_data( '_subscription_period_interval', '1' );
			$p->update_meta_data( '_subscription_length', '0' );
			$p->update_meta_data( '_subscription_sign_up_fee', '5' );
		}
	);

	$parent = $order_once(
		'_pulsar_ext_subscription',
		function ( $order ) use ( $box ) {
			$order->add_product( $box, 1 );
			$order->set_status( 'completed' );
		}
	);

	if ( $parent ) {
		$subscription = wcs_create_subscription(
			[
				'order_id'         => $parent->get_id(),
				'customer_id'      => $user->ID,
				'billing_period'   => 'month',
				'billing_interval' => 1,
				'start_date'       => gmdate( 'Y-m-d H:i:s', strtotime( '-20 days' ) ),
			]
		);
		$subscription->add_product( $box, 1 );
		$subscription->set_address( $customer->get_billing(), 'billing' );
		$subscription->set_address( $customer->get_shipping(), 'shipping' );
		$subscription->set_payment_method( 'bacs' );
		$subscription->calculate_totals();
		$subscription->update_dates( [ 'next_payment' => gmdate( 'Y-m-d H:i:s', strtotime( '+10 days' ) ) ] );
		$subscription->update_status( 'active' );
	}

	$out[] = 'subscriptions';
}

// Product Bundles: one tee (so its quantity in the basket is fixed) and
// optionally one or two mugs.
if ( class_exists( 'WC_Product_Bundle' ) ) {
	$bundle = $product(
		'pulsar-ext-bundle',
		WC_Product_Bundle::class,
		function ( $p ) {
			$p->set_name( 'Ext gift bundle' );
			$p->set_regular_price( '25' );
		}
	);

	if ( ! WC_PB_DB::query_bundled_items( [ 'bundle_id' => $bundle->get_id() ] ) ) {
		foreach ( [ $tee, $mug ] as $i => $item ) {
			WC_PB_DB::add_bundled_item(
				[
					'bundle_id'  => $bundle->get_id(),
					'product_id' => $item->get_id(),
					'menu_order' => $i,
					'meta_data'  => [
						'quantity_min'        => 1,
						'quantity_max'        => 1 + $i,
						'priced_individually' => 'no',
						'optional'            => 1 === $i ? 'yes' : 'no',
					],
				]
			);
		}

		wc_get_product( $bundle->get_id() )->save();
	}

	$out[] = 'product bundles';
}

// Bookings: an hour's workshop for up to four people, 9 to 5, and the
// customer booked on one.
if ( class_exists( 'WC_Product_Booking' ) ) {
	$workshop = $product(
		'pulsar-ext-workshop',
		WC_Product_Booking::class,
		function ( $p ) {
			$p->set_name( 'Ext workshop' );
			$p->set_duration( 1 );
			$p->set_duration_unit( 'hour' );
			$p->set_duration_type( 'fixed' );
			$p->set_cost( 30 );
			$p->set_block_cost( 0 );
			$p->set_default_date_availability( 'non-available' );
			$p->set_min_date_value( 1 );
			$p->set_min_date_unit( 'day' );
			$p->set_max_date_value( 3 );
			$p->set_max_date_unit( 'month' );
			$p->set_qty( 4 );
			$p->set_has_persons( true );
			$p->set_min_persons( 1 );
			$p->set_max_persons( 4 );
			$p->set_availability(
				[
					[
						'type'     => 'time',
						'bookable' => 'yes',
						'priority' => 10,
						'from'     => '09:00',
						'to'       => '17:00',
					],
				]
			);
		}
	);

	$item_id = 0;
	$order   = $order_once(
		'_pulsar_ext_booking',
		function ( $order ) use ( $workshop, &$item_id ) {
			$item_id = $order->add_product( $workshop, 1 );
			$order->set_status( 'processing' );
		}
	);

	if ( $order ) {
		$start = strtotime( 'next monday 10:00' );
		create_wc_booking(
			$workshop->get_id(),
			[
				'start_date'    => $start,
				'end_date'      => $start + HOUR_IN_SECONDS,
				'user_id'       => $user->ID,
				'order_item_id' => $item_id,
				'persons'       => 2,
				'cost'          => 30,
			],
			'confirmed',
			true
		);
	}

	// Bookings earlier runs left in a basket, which hold their places.
	foreach ( get_posts( [ 'post_type' => 'wc_booking', 'post_status' => [ 'in-cart', 'was-in-cart' ], 'numberposts' => -1, 'fields' => 'ids' ] ) as $id ) {
		wp_delete_post( $id, true );
	}

	$out[] = 'bookings';
}

// Memberships: a plan the customer is a member of, a page only members can
// read, and 10% off the mug for members.
if ( function_exists( 'wc_memberships' ) ) {
	$plan = get_page_by_path( 'pulsar-ext-members', OBJECT, 'wc_membership_plan' );

	$plan_id = $plan ? $plan->ID : wp_insert_post(
		[
			'post_type'   => 'wc_membership_plan',
			'post_status' => 'publish',
			'post_title'  => 'Ext members club',
			'post_name'   => 'pulsar-ext-members',
			'meta_input'  => [
				'_access_method' => 'manual-only',
				'_access_length' => '',
			],
		]
	);

	$page    = get_page_by_path( 'pulsar-ext-members-only' );
	$page_id = $page ? $page->ID : wp_insert_post(
		[
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Members only',
			'post_name'    => 'pulsar-ext-members-only',
			'post_content' => "<!-- wp:paragraph -->\n<p>Only members can read this.</p>\n<!-- /wp:paragraph -->",
		]
	);

	wc_memberships()->get_rules_instance()->add_rules(
		[
			[
				'id'                 => 'pulsar_ext_members_only',
				'membership_plan_id' => $plan_id,
				'rule_type'          => 'content_restriction',
				'content_type'       => 'post_type',
				'content_type_name'  => 'page',
				'object_ids'         => [ $page_id ],
				'access_type'        => 'immediate',
			],
			[
				'id'                 => 'pulsar_ext_member_price',
				'membership_plan_id' => $plan_id,
				'rule_type'          => 'purchasing_discount',
				'content_type'       => 'post_type',
				'content_type_name'  => 'product',
				'object_ids'         => [ $mug->get_id() ],
				'discount_type'      => 'percentage',
				'discount_amount'    => '10',
				'active'             => 'yes',
			],
		]
	);

	// The restriction message alone, no excerpt of what's restricted.
	update_option( 'wc_memberships_show_excerpts', 'no' );

	if ( ! wc_memberships_is_user_member( $user->ID, $plan_id ) ) {
		wc_memberships_create_user_membership(
			[
				'plan_id' => $plan_id,
				'user_id' => $user->ID,
			]
		);
	}

	$out[] = 'memberships';
}

echo 'Seeded: ' . ( $out ? implode( ', ', $out ) : 'no extensions active' ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
