<?php
/**
 * Plugin Name: ePagos Payment Gateway for WooCommerce
 * Plugin URI: https://github.com/yourusername/wc-epagos-gateway
 * Description: Accept payments in Argentina using ePagos payment gateway
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wc-epagos-gateway
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 6.0
 * WC tested up to: 8.5
 *
 * @package WC_ePagos_Gateway
 */

defined( 'ABSPATH' ) || exit;

// Define plugin constants
define( 'WC_EPAGOS_VERSION', '1.0.0' );
define( 'WC_EPAGOS_PLUGIN_FILE', __FILE__ );
define( 'WC_EPAGOS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WC_EPAGOS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if WooCommerce is active and initialize the gateway
 */
function wc_epagos_gateway_init() {
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', 'wc_epagos_missing_wc_notice' );
        return;
    }

    // Include the gateway class
    require_once WC_EPAGOS_PLUGIN_DIR . 'includes/class-wc-epagos-gateway.php';

    // Add the gateway to WooCommerce
    add_filter( 'woocommerce_payment_gateways', 'wc_epagos_add_gateway' );
}
add_action( 'plugins_loaded', 'wc_epagos_gateway_init', 11 );

/**
 * Add ePagos Gateway to WooCommerce
 *
 * @param array $gateways Existing gateways.
 * @return array Modified gateways.
 */
function wc_epagos_add_gateway( $gateways ) {
    $gateways[] = 'WC_ePagos_Gateway';
    return $gateways;
}

/**
 * Display admin notice if WooCommerce is not active
 */
function wc_epagos_missing_wc_notice() {
    echo '<div class="error"><p><strong>' . 
         esc_html__( 'ePagos Payment Gateway requires WooCommerce to be installed and active.', 'wc-epagos-gateway' ) . 
         '</strong></p></div>';
}

/**
 * Add plugin action links
 *
 * @param array $links Existing links.
 * @return array Modified links.
 */
function wc_epagos_plugin_action_links( $links ) {
    $plugin_links = array(
        '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=epagos' ) . '">' . 
        esc_html__( 'Settings', 'wc-epagos-gateway' ) . '</a>',
    );
    return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_epagos_plugin_action_links' );

/**
 * Load plugin textdomain for translations
 */
function wc_epagos_load_textdomain() {
    load_plugin_textdomain( 'wc-epagos-gateway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'wc_epagos_load_textdomain' );

/**
 * Declare compatibility with WooCommerce HPOS (High-Performance Order Storage)
 */
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );
