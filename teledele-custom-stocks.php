<?php
/**
 * Plugin Name: Teledele Custom Stocks
 * Version: 0.1.0
 * Author: The WordPress Contributors
 * Author URI: https://woocommerce.com
 * Text Domain: teledele-custom-stocks
 * Domain Path: /languages
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package extension
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE' ) ) {
	define( 'TELEDELE_CUSTOM_STOCKS_MAIN_PLUGIN_FILE', __FILE__ );
}

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

use TeledeleCustomStocks\Admin\Setup;

// phpcs:disable WordPress.Files.FileName

/**
 * WooCommerce fallback notice.
 *
 * @since 0.1.0
 */
function teledele_custom_stocks_missing_wc_notice() {
	/* translators: %s WC download URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Teledele Custom Stocks requires WooCommerce to be installed and active. You can download %s here.', 'teledele_custom_stocks' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

register_activation_hook( __FILE__, 'teledele_custom_stocks_activate' );

/**
 * Activation hook.
 *
 * @since 0.1.0
 */
function teledele_custom_stocks_activate() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'teledele_custom_stocks_missing_wc_notice' );
		return;
	}
}

if ( ! class_exists( 'TeledeleCustomStocks' ) ) :
	/**
	 * The TeledeleCustomStocks class.
	 */
	class TeledeleCustomStocks {
		/**
		 * This class instance.
		 *
		 * @var \TeledeleCustomStocks single instance of this class.
		 */
		private static $instance;

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( is_admin() ) {
				new Setup();
			}
			new TeledeleCustomStocks\Product();
		}

		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'teledele_custom_stocks' ), $this->version );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'teledele_custom_stocks' ), $this->version );
		}

		/**
		 * Gets the main instance.
		 *
		 * Ensures only one instance can be loaded.
		 *
		 * @return \teledele_custom_stocks
		 */
		public static function instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
endif;

add_action( 'plugins_loaded', 'teledele_custom_stocks_init', 10 );

/**
 * Initialize the plugin.
 *
 * @since 0.1.0
 */
function teledele_custom_stocks_init() {
	load_plugin_textdomain( 'teledele_custom_stocks', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'teledele_custom_stocks_missing_wc_notice' );
		return;
	}

	TeledeleCustomStocks::instance();
}
