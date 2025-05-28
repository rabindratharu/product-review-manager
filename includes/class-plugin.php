<?php

/**
 * Plugin.
 *
 * @package product-review-manager
 * @since 1.0.0
 */

namespace Product_Review_Manager;

use Product_Review_Manager\Assets;
use Product_Review_Manager\Register_Post_Types;
use Product_Review_Manager\Meta_Boxes;
use Product_Review_Manager\Utils\Helper;
use Product_Review_Manager\Utils\Singleton;

/**
 * Plugin Main Class
 *
 * @since 1.0.0
 */
final class Plugin
{

    use Singleton;

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
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cloning is forbidden.', 'product-review-manager'), '1.0.0');
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Unserializing instances of this class is forbidden.', 'product-review-manager'), '1.0.0');
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
        Assets::get_instance();
        Helper::get_instance();
        Register_Post_Types::get_instance();
        Meta_Boxes::get_instance();
    }
}
