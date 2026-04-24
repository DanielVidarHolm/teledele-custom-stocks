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
		add_action( 'admin_init', array( $this, 'register_page' ) );

		new ProductFields();
	}

	/**
	 * Load all necessary dependencies.
	 *
	 * @since 1.0.0
	 */
    public function register_scripts() {
        if (
            ! method_exists( 'Automattic\WooCommerce\Admin\PageController', 'is_admin_or_embed_page' ) ||
            ! \Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page()
        ) {
            return;
        }

        $base_dir = dirname( TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE );

        // JS must exist; if it doesn't (or is mid-rebuild), don't enqueue.
        $script_rel_path = '/build/index.js';
        $script_abs_path = $base_dir . $script_rel_path;

        if ( ! file_exists( $script_abs_path ) ) {
            return;
        }

        $script_asset_path = $base_dir . '/build/index.asset.php';
        $script_asset      = file_exists( $script_asset_path )
            ? require $script_asset_path
            : array(
                'dependencies' => array(),
                'version'      => filemtime( $script_abs_path ),
            );

        wp_register_script(
            'teledele-custom-stocks',
            plugins_url( $script_rel_path, TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE ),
            $script_asset['dependencies'],
            $script_asset['version'],
            true
        );

        wp_enqueue_script( 'teledele-custom-stocks' );

        // CSS in dev is style-index.css (your terminal output confirms this).
        $css_candidates = array(
            '/build/style-index.css', // emitted by `npm run start`
            '/build/index.css',       // sometimes emitted by `npm run build`
        );

        foreach ( $css_candidates as $css_rel_path ) {
            $css_abs_path = $base_dir . $css_rel_path;

            if ( file_exists( $css_abs_path ) ) {
                wp_register_style(
                    'teledele-custom-stocks',
                    plugins_url( $css_rel_path, TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE ),
                    array(),
                    filemtime( $css_abs_path )
                );

                wp_enqueue_style( 'teledele-custom-stocks' );
                break;
            }
        }
    }

	/**
	 * Register page in wc-admin.
	 *
	 * @since 1.0.0
	 */
	public function register_page() {
        error_log( 'TCS register_page ran. wc_admin_register_page=' . ( function_exists('wc_admin_register_page') ? 'YES' : 'NO' ) );

        if ( ! function_exists( 'wc_admin_register_page' ) ) {
			return;
		}

		wc_admin_register_page(
			array(
                'id'         => 'teledele_custom_stocks',
                'title'      => __( 'Teledele Custom Stocks', 'teledele-custom-stocks' ),
                'parent'     => 'woocommerce',
                'path'       => '/teledele-custom-stocks',
                'capability' => 'manage_woocommerce',
			)
		);
	}
}
