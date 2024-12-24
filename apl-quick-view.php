<?php
/**
 * Plugin Name: APL Quick View
 * Plugin URI: https://github.com/shivalgo/apl-quick-view
 * Description: A simple and powerful WooCommerce plugin for adding a quick view button to product pages.
 * Version: 1.0.1
 * Author: Algorithus Pvt. Ltd.
 * Author URI: https://algorithus.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: apl-quick-view
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define('APL_QUICK_VIEW_BASE_PATH', plugin_dir_path(__FILE__));
define('APL_QUICK_VIEW_BASE_URL', plugin_dir_url(__FILE__));
define('APL_QUICK_VIEW_DIR_NAME', basename(APL_QUICK_VIEW_BASE_PATH));

// Load text domain for translations
function awp_wc_quick_view_load_textdomain() {
    load_plugin_textdomain('apl-quick-view', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
awp_wc_quick_view_load_textdomain();

// Check for required files
$required_files = array(
    APL_QUICK_VIEW_BASE_PATH . 'includes/default-options.php',
    APL_QUICK_VIEW_BASE_PATH . 'includes/apl-quick-view-class.php',
);

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        wp_die(esc_html__('Required files for APL Quick View are missing.', 'apl-quick-view'));
    }
}

require_once(APL_QUICK_VIEW_BASE_PATH . 'includes/default-options.php');
require_once(APL_QUICK_VIEW_BASE_PATH . 'includes/apl-quick-view-class.php');


/**
 * Check if WooCommerce is active and initialize the plugin.
 */
function awp_wc_quick_view_check_dependencies() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'awp_wc_quick_view_dependency_notice');
        return;
    }

    // Initialize the plugin instance
    APL_Quick_View::get_instance();
}
add_action('plugins_loaded', 'awp_wc_quick_view_check_dependencies');

/**
 * Display an admin notice if WooCommerce is not active.
 */
function awp_wc_quick_view_dependency_notice() {
    echo sprintf(
        '<div class="notice notice-error"><p>%s</p></div>',
        esc_html__('APL Quick View plugin requires WooCommerce to be installed and activated.', 'apl-quick-view')
    );
}

/**
 * Register plugin activation and deactivation hooks.
 */
register_activation_hook(__FILE__, array('APL_Quick_View', 'on_activation'));
register_deactivation_hook(__FILE__, array('APL_Quick_View', 'on_deactivation'));