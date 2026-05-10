<?php
/**
 * Plugin Name: PricZone AI Concierge
 * Description: AI shopping concierge for WooCommerce with product discovery, recommendations, category-aware retrieval, admin tabs, logs, and customizable storefront widget.
 * Version: 5.3.0
 * Author: Derek Williams
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) exit;

define('PZAI_VERSION', '5.3.0');
define('PZAI_PLUGIN_FILE', __FILE__);
define('PZAI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PZAI_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once PZAI_PLUGIN_PATH . 'includes/class-pzai-plugin.php';

function pzai_boot_plugin() {
    return \PZAI\Plugin::instance();
}
add_action('plugins_loaded', 'pzai_boot_plugin');
