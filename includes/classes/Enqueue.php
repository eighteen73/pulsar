<?php
/**
 * Theme assets enqueue.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Asset;
use Pulsar\Tools\Config;

/**
 * Enqueue scripts, styles and fonts.
 */
class Enqueue implements Bootable {

	/**
	 * Block stylesheets that build on another block's: block => blocks.
	 *
	 * A gallery's carousel layout uses the carousel's markup without being a
	 * carousel block, so the carousel's stylesheet wouldn't load with it.
	 */
	private const STYLE_DEPENDENCIES = [
		'matter/gallery' => [ 'matter/carousel' ],
	];

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'theme_styles' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'theme_scripts' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'woocommerce_styles' ], 20 );
		add_action( 'after_setup_theme', [ $this, 'block_styles' ] );
		add_action( 'init', [ $this, 'register_block_style_dependencies' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'disabled_block_styles' ], 999 );
		add_action( 'after_setup_theme', [ $this, 'editor_styles' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_styles' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 10 );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return true;
	}

	/**
	 * Theme stylesheets.
	 *
	 * @return void
	 */
	public function theme_styles(): void {

		wp_enqueue_style(
			'pulsar-app-styles',
			get_theme_file_uri( 'build/css/app.css' ),
			[],
			Asset::attribute( 'app', 'css', 'version' ),
			'screen, print',
		);
	}

	/**
	 * Theme scripts.
	 *
	 * @return void
	 */
	public function theme_scripts(): void {

		wp_enqueue_script_module(
			'@pulsar/app',
			get_theme_file_uri( 'build/js/app.js' ),
			Asset::attribute( 'app', 'js', 'dependencies' ),
			Asset::attribute( 'app', 'js', 'version' ),
		);
	}

	/**
	 * WooCommerce stylesheets: its classic markup (forms, tables, order details,
	 * payment methods) on every WooCommerce page, as it appears on My Account,
	 * the customer payment page, order received and the classic cart and
	 * checkout; and My Account's layout. After WooCommerce's own stylesheet,
	 * which they override.
	 *
	 * @return void
	 */
	public function woocommerce_styles(): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		if ( ! ( \is_woocommerce() || \is_cart() || \is_checkout() || \is_account_page() ) ) {
			return;
		}

		// WooCommerce registers it at this hook's default priority; a site can
		// switch it off (`woocommerce_enqueue_styles`).
		$after = wp_style_is( 'woocommerce-general', 'registered' ) ? [ 'woocommerce-general' ] : [];

		wp_enqueue_style(
			'pulsar-woocommerce-styles',
			get_theme_file_uri( 'build/css/woocommerce.css' ),
			array_merge( Asset::attribute( 'woocommerce', 'css', 'dependencies' ), $after ),
			Asset::attribute( 'woocommerce', 'css', 'version' ),
			'screen, print',
		);

		if ( \is_account_page() ) {
			wp_enqueue_style(
				'pulsar-woocommerce-account-styles',
				get_theme_file_uri( 'build/css/woocommerce-account.css' ),
				array_merge( Asset::attribute( 'woocommerce-account', 'css', 'dependencies' ), [ 'pulsar-woocommerce-styles' ] ),
				Asset::attribute( 'woocommerce-account', 'css', 'version' ),
				'screen, print',
			);
		}
	}

	/**
	 * Enqueues block-specific stylesheets so that they only load when the block
	 * is in use. Each stylesheet built from
	 * `src/css/blocks/{namespace}/{block}.scss` loads with the block
	 * `{namespace}/{block}`, e.g. `blocks/matter/gallery.scss` with
	 * `matter/gallery`.
	 *
	 * @link  https://developer.wordpress.org/reference/functions/wp_enqueue_block_style/
	 *
	 * @return void
	 */
	public function block_styles(): void {
		foreach ( self::block_stylesheets() as $block => $file ) {
			wp_enqueue_block_style(
				$block,
				[
					'handle' => self::block_style_handle( $block ),
					'src'    => get_theme_file_uri( "build/css/blocks/{$file}.css" ),
					'path'   => get_theme_file_path( "build/css/blocks/{$file}.css" ),
					'deps'   => self::block_style_dependencies( $block, $file ),
					'ver'    => Asset::attribute( basename( $file ), 'css/blocks/' . dirname( $file ), 'version' ),
					'media'  => 'screen, print',
				],
			);
		}
	}

	/**
	 * Register the block stylesheets others depend on, so a dependency loads
	 * even where its own block isn't on the page.
	 *
	 * @return void
	 */
	public function register_block_style_dependencies(): void {
		$stylesheets = self::block_stylesheets();

		foreach ( array_unique( array_merge( ...array_values( self::STYLE_DEPENDENCIES ) ) ) as $block ) {
			if ( ! isset( $stylesheets[ $block ] ) ) {
				continue;
			}

			$file = $stylesheets[ $block ];

			wp_register_style(
				self::block_style_handle( $block ),
				get_theme_file_uri( "build/css/blocks/{$file}.css" ),
				[],
				Asset::attribute( basename( $file ), 'css/blocks/' . dirname( $file ), 'version' ),
				'screen, print'
			);
			wp_style_add_data( self::block_style_handle( $block ), 'path', get_theme_file_path( "build/css/blocks/{$file}.css" ) );
		}
	}

	/**
	 * The built block stylesheets, by block name.
	 *
	 * @return array<string, string> Block name => path in build/css/blocks without `.css`.
	 */
	private static function block_stylesheets(): array {
		$stylesheets = [];

		foreach ( glob( get_theme_file_path( 'build/css/blocks/*/*.css' ) ) ?: [] as $path ) {
			// Right-to-left copies are WordPress's to swap in.
			if ( str_ends_with( $path, '-rtl.css' ) ) {
				continue;
			}

			$file  = basename( dirname( $path ) ) . '/' . basename( $path, '.css' );
			$block = strtolower( $file );

			if ( preg_match( '#^[a-z0-9-]+/[a-z0-9-]+$#', $block ) ) {
				$stylesheets[ $block ] = $file;
			}
		}

		return $stylesheets;
	}

	/**
	 * A block stylesheet's handle, e.g. `pulsar-block-matter-gallery`.
	 *
	 * @param string $block Block name.
	 * @return string
	 */
	private static function block_style_handle( string $block ): string {
		return 'pulsar-block-' . str_replace( '/', '-', $block );
	}

	/**
	 * A block stylesheet's dependencies: its build's, plus the stylesheets
	 * of the blocks it builds on (STYLE_DEPENDENCIES).
	 *
	 * @param string $block Block name.
	 * @param string $file  Path in build/css/blocks without `.css`.
	 * @return array
	 */
	private static function block_style_dependencies( string $block, string $file ): array {
		return array_merge(
			(array) Asset::attribute( basename( $file ), 'css/blocks/' . dirname( $file ), 'dependencies' ),
			array_map( [ self::class, 'block_style_handle' ], self::STYLE_DEPENDENCIES[ $block ] ?? [] )
		);
	}

	/**
	 * Disable individual block stylesheets.
	 *
	 * @return void
	 */
	public function disabled_block_styles(): void {

		$blocks = Config::get( 'disabled-block-styles' );

		if ( ! $blocks ) {
			return;
		}

		foreach ( $blocks as $block ) {
			wp_dequeue_style( "wp-block-{$block}" );
		}
	}

	/**
	 * Editor stylesheets.
	 *
	 * @return void
	 */
	public function editor_styles(): void {

		add_editor_style(
			[
				'build/css/editor.css',
			]
		);
	}

	/**
	 * Editor scripts.
	 *
	 * @return void
	 */
	public function editor_scripts(): void {

		wp_enqueue_script(
			'pulsar-editor-scripts',
			get_theme_file_uri( 'build/js/editor.js' ),
			Asset::attribute( 'editor', 'js', 'dependencies' ),
			Asset::attribute( 'editor', 'js', 'version' ),
			[
				'in_footer' => true,
			],
		);
	}

	/**
	 * Admin stylesheets.
	 *
	 * @return void
	 */
	public function admin_styles(): void {}

	/**
	 * Admin scripts.
	 *
	 * @return void
	 */
	public function admin_scripts(): void {}
}
