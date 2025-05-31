<?php

/**
 * Admin Page.
 *
 * @package product-review-manager
 * @since 1.0.0
 */

namespace Product_Review_Manager\Admin;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Product_Review_Manager\Utils\Singleton;
use Product_Review_Manager\Utils\Helper;

/**
 * Class Page
 */
class Page
{
    use Singleton;

    /**
     * Menu info.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $menu_info    Admin menu information.
     */
    private $menu_info;

    /**
     * Construct method.
     *
     * Initializes the class and sets up necessary hooks.
     */
    protected function __construct()
    {
        $this->setup_hooks();
    }

    /**
     * Set up hooks for the class.
     *
     * @return void
     */
    protected function setup_hooks()
    {
        add_action('admin_menu', [$this, 'add_admin_menu'], 99);
        add_filter('admin_body_class', [$this, 'add_has_sticky_header']);
        add_action('admin_enqueue_scripts', [$this, 'register_admin_assets']);

        add_action('rest_api_init', [$this, 'register_settings']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Add Admin Page Menu page.
     *
     * @access public
     *
     * @since    1.0.0
     */
    public function add_admin_menu()
    {
        $this->menu_info = Helper::white_label()['admin_menu_page'];
        add_menu_page(
            $this->menu_info['page_title'],
            $this->menu_info['menu_title'],
            'manage_options',
            $this->menu_info['menu_slug'],
            [$this, 'render_page_callback'],
            $this->menu_info['icon_url'],
            $this->menu_info['position']
        );
    }

    /**
     * Check if current menu page.
     *
     * @access public
     *
     * @since    1.0.0
     * @return boolean ture if current menu page else false.
     */
    public function is_menu_page()
    {
        $screen              = get_current_screen();
        $admin_scripts_bases = array('toplevel_page_' . PRM_OPTION_NAME);
        if (! (isset($screen->base) && in_array($screen->base, $admin_scripts_bases, true))) {
            return false;
        }
        return true;
    }

    /**
     * Add class "at-has-hdr-stky".
     *
     * @access public
     * @since    1.0.0
     * @param string $classes  a space-separated string of class names.
     * @return string $classes with added class if confition meet.
     */
    public function add_has_sticky_header($classes)
    {

        if (! $this->is_menu_page()) {
            return $classes;
        }

        return $classes . ' at-has-hdr-stky ';
    }

    /**
     * Renders root div for React.
     *
     * @return void
     * @since 1.0.0
     */
    public function render_page_callback()
    {
        echo '<div id="' . esc_attr(PRM_OPTION_NAME) . '"></div>';
    }

    /**
     * Registers and enqueues assets.
     *
     * @return void
     */
    public function register_admin_assets()
    {

        if (! $this->is_menu_page()) {
            return;
        }

        $suffix = is_rtl() ? '-rtl' : '';
        // Register styles.
        wp_register_style('atomic', PRM_BUILD_PATH_URL . "/library/atomic-css/atomic.min{$suffix}.css", [], filemtime(PRM_BUILD_PATH . "/library/atomic-css/atomic.min{$suffix}.css"), 'all');
        wp_register_style('prm-admin', PRM_BUILD_PATH_URL . "/admin/index{$suffix}.css", ['atomic', 'wp-components'], filemtime(PRM_BUILD_PATH . "/admin/index{$suffix}.css"), 'all');

        // Enqueue Styles.
        wp_enqueue_style('prm-admin');


        $asset_config_file = sprintf('%s/admin/index.asset.php', PRM_BUILD_PATH);

        if (! file_exists($asset_config_file)) {
            return;
        }

        $editor_asset   = include_once $asset_config_file;
        $js_dependencies = (! empty($editor_asset['dependencies'])) ? $editor_asset['dependencies'] : [];
        $version         = (! empty($editor_asset['version'])) ? $editor_asset['version'] : filemtime($asset_config_file);

        // Register scripts.
        wp_register_script(
            'prm-admin',
            PRM_BUILD_PATH_URL . '/admin/index.js',
            $js_dependencies,
            $version,
            true
        );

        // Enqueue scripts.
        wp_enqueue_script('prm-admin');

        /* Localize */
        $localize = apply_filters(
            'prm_admin_localize',
            array(
                'version'     => $version,
                'root_id'     => PRM_PLUGIN_NAME,
                'nonce'       => wp_create_nonce('wp_rest'),
                'store'       => PRM_PLUGIN_NAME . '-store',
                'rest_url'    => get_rest_url(),
                'white_label' => Helper::white_label(),
            )
        );
        wp_set_script_translations('prm-admin', 'prm-admin');
        wp_localize_script('prm-admin', 'WpReactPluginBoilerplateLocalize', $localize);
    }

    /**
     * Register settings.
     * Common callback function of rest_api_init and admin_init
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function register_settings()
    {
        register_setting(
            'prm_settings_group',
            PRM_OPTION_NAME,
            array(
                'type'         => 'object',
                'default'      => Helper::get_default_options(),
                'show_in_rest' => array(
                    'schema' => Helper::get_settings_schema(),
                ),
            )
        );
    }
}