<?php

/**
 * Plugin Name:       Product Review Manager
 * Description:       A WordPress plugin to manage product reviews with a custom post type, meta fields, shortcode, and REST API.
 * Version:           1.0.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Rabindra Tharu
 * Author URI:        https://github.com/rabindratharu
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       product-review-manager
 *
 * @package product-review-manager
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Define plugin constants.
 */
define('PRM_PATH', plugin_dir_path(__FILE__));
define('PRM_URL', plugin_dir_url(__FILE__));
define('PRM_BASENAME', plugin_basename(__FILE__));
define('PRM_BUILD_PATH', PRM_PATH . 'assets/build');
define('PRM_BUILD_PATH_URL', PRM_URL . 'assets/build');
define('PRM_PLUGIN_NAME', 'product-review-manager');
define('PRM_OPTION_NAME', 'product-review-manager');

/**
 * Bootstrap the plugin.
 */
require_once PRM_PATH . 'includes/utils/autoloader.php';

use Product_Review_Manager\Plugin;

// Check if the class exists and WordPress environment is valid
if (class_exists('Product_Review_Manager\Plugin')) {
    // Instantiate the plugin
    $the_plugin = Plugin::get_instance();

    // Register activation and deactivation hooks
    register_activation_hook(__FILE__, [$the_plugin, 'activate']);
    register_deactivation_hook(__FILE__, [$the_plugin, 'deactivate']);
}