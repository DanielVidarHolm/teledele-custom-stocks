<?php

namespace TeledeleCustomStocks\Admin;

/**
 * TeledeleCustomStocks Setup Class
 */
class Setup {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_menu', array( $this, 'register_page' ) );

		new ProductFields();
	}

	/**
	 * Load all necessary dependencies.
	 *
	 * @since 1.0.0
	 */
	public function register_scripts() {
		if ( ! method_exists( 'Automattic\WooCommerce\Admin\PageController', 'is_admin_or_embed_page' ) ||
		! \Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page()
		) {
			return;
		}

		$script_path       = '/build/index.js';
		$script_asset_path = dirname( TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE ) . '/build/index.asset.php';
		$script_asset      = file_exists( $script_asset_path )
		? require $script_asset_path
		: array(
			'dependencies' => array(),
			'version'      => filemtime( dirname( TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE ) . $script_path ),
		);
		$script_url        = plugins_url( $script_path, TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE );

		wp_register_script(
			'teledele-custom-stocks',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_register_style(
			'teledele-custom-stocks',
			plugins_url( '/build/index.css', TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE ),
			// Add any dependencies styles may have, such as wp-components.
			array(),
			filemtime( dirname( TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE ) . '/build/index.css' )
		);

		wp_enqueue_script( 'teledele-custom-stocks' );
		wp_enqueue_style( 'teledele-custom-stocks' );
	}

	/**
	 * Register page in wc-admin.
	 *
	 * @since 1.0.0
	 */
	public function register_page() {

		if ( ! function_exists( 'wc_admin_register_page' ) ) {
			return;
		}

		wc_admin_register_page(
			array(
				'id'     => 'teledele_custom_stocks-example-page',
				'title'  => __( 'Teledele Custom Stocks', 'teledele_custom_stocks' ),
				'parent' => 'woocommerce',
				'path'   => '/teledele-custom-stocks',
			)
		);
	}
}
