<?php
return [
	NewTheme\Setup::class => new NewTheme\Setup(),
	NewTheme\Enqueue::class => new NewTheme\Enqueue(),
	NewTheme\Editor\Blocks::class => new NewTheme\Editor\Blocks(),
	NewTheme\Editor\Styles::class => new NewTheme\Editor\Styles(),
	NewTheme\Editor\Patterns::class => new NewTheme\Editor\Patterns(),
	NewTheme\WooCommerce\Setup::class => new NewTheme\WooCommerce\Setup(),
	NewTheme\WooCommerce\SingleProduct::class => new NewTheme\WooCommerce\SingleProduct(),
];
