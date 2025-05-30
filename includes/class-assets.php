<?php

/**
 * Enqueue assets.
 *
 * @package product-review-manager
 * @since 1.0.0
 */

namespace Product_Review_Manager;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Product_Review_Manager\Utils\Singleton;

/**
 * Class Assets
 */
class Assets
{
    use Singleton;

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
        add_action('wp_enqueue_scripts', [$this, 'register_styles']);
    }

    /**
     * Register and enqueue styles for the theme.
     *
     * @return void
     */
    public function register_styles()
    {
        $suffix = is_rtl() ? '-rtl' : '';
        // Register styles.
        wp_register_style('main', PRM_BUILD_PATH_URL . "/main/index{$suffix}.css", [], filemtime(PRM_BUILD_PATH . "/main/index{$suffix}.css"), 'all');
        // Enqueue Styles.
        wp_enqueue_style('main');
    }
}
