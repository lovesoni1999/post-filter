<?php
/*
Plugin Name: Product Filter
Plugin URI: https://wordpress.org/plugins/
Description: Product or post update using filters.
Version: 1.0.0
Author: Love Soni
Author URI: https://profiles.wordpress.org/lovesoni1999/
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: product-filter
Domain Path: /languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


// Define plugin dir path.
if ( ! defined( 'P_FILTER_PATH' ) ) {
    define( 'P_FILTER_PATH', plugin_dir_path( __FILE__ ) );
}

// Define plugin dir url.
if ( ! defined( 'P_FILTER_URL' ) ) {
    define( 'P_FILTER_URL', plugin_dir_url( __FILE__ ) );
}

// Define default per page.
if ( ! defined( '' ) ) {
    define( 'P_FILTER_PER_PAGE', 6 );
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-product-filter.php';

register_activation_hook( __FILE__, 'activate_product_filter' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activator.php
 */
function activate_product_filter() {
    require_once ('includes/activator.php');
    P_FILTER_Activator::activate();
}

register_deactivation_hook( __FILE__, 'deactivate_product_filter' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivator.php
 */
function deactivate_product_filter() {
    require_once ('includes/deactivator.php');
    P_FILTER_Deactivate::deactivate();
}

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function init_product_filter() {
    PRODUCT_FILTER::get_instance();
}

add_action( 'plugins_loaded', 'init_product_filter' );
