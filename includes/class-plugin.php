<?php

/**
 * Plugin.
 *
 * @package product-review-manager
 * @since 1.0.0
 */

namespace Product_Review_Manager;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Product_Review_Manager\Assets;
use Product_Review_Manager\Register_Post_Types;
use Product_Review_Manager\Meta_Boxes;
use Product_Review_Manager\Reviews;
use Product_Review_Manager\Utils\Helper;
use Product_Review_Manager\Utils\Singleton;
use Product_Review_Manager\Api\Rest_Endpoint;
use Product_Review_Manager\Api\Api_Settings;
use Product_Review_Manager\Admin\Page;

/**
 * Plugin Main Class
 *
 * @since 1.0.0
 */
final class Plugin
{

    use Singleton;

    /**
     * Plugin version
     */
    public const VERSION = '1.0.0';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        $this->set_locale();
        add_action('plugins_loaded', [$this, 'setup_classes']);
    }

    /**
     * Method to execute tasks on plugin activation.
     *
     * This function is triggered when the plugin is activated.
     * It can be used to set up default options, create necessary database tables,
     * or perform any other initial setup required by the plugin.
     *
     * @since 1.0.0
     * @return void
     */

    public function activate()
    {
        $current_version = get_option('prm_plugin_version', '0.0.0');
        $new_version = self::VERSION; // Replace with your plugin version

        if (version_compare($current_version, $new_version, '<')) {
            // Flush rewrite rules on update
            flush_rewrite_rules();
            update_option('prm_plugin_version', $new_version);
        }
    }

    /**
     * Method to execute tasks on plugin deactivation.
     *
     * This function is triggered when the plugin is deactivated.
     * It can be used to clean up any resources or data associated with the plugin.
     *
     * @since 1.0.0
     * @return void
     */
    public function deactivate()
    {
        flush_rewrite_rules();
    }

    /**
     * Prevent cloning of the plugin instance
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Cloning is forbidden.', 'product-review-manager'),
            self::VERSION
        );
    }

    /**
     * Prevent unserializing of the plugin instance
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(
            __FUNCTION__,
            esc_html__('Unserializing instances of this class is forbidden.', 'product-review-manager'),
            self::VERSION
        );
    }

    /**
     * Set up plugin localization.
     *
     * @since 1.0.0
     * @return void
     */
    private function set_locale()
    {
        add_action('plugins_loaded', [$this, 'load_textdomain']);
    }

    /**
     * Load plugin text domain for translations.
     *
     * @since 1.0.0
     * @return void
     */
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'product-review-manager',
            false,
            dirname(PRM_BASENAME) . '/languages'
        );
    }

    /**
     * Initialize plugin classes.
     *
     * @since 1.0.0
     * @return void
     */
    public function setup_classes()
    {
        // Core functionality classes
        Register_Post_Types::get_instance();
        Meta_Boxes::get_instance();
        Reviews::get_instance();

        // Utility classes
        Helper::get_instance();

        // Frontend/backend assets
        Assets::get_instance();

        // REST API
        Rest_Endpoint::get_instance();
        Api_Settings::get_instance();

        // Admin
        Page::get_instance();
    }
}