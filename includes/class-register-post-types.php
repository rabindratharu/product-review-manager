<?php

/**
 * Register Custom Post Types
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
 * Register Post Types class.
 *
 * Handles registration of custom post types for the current theme/plugin.
 *
 * @since 1.0.0
 */
class Register_Post_Types
{
    use Singleton;

    /**
     * Private constructor to prevent direct object creation.
     *
     * Sets up hooks for post type registration.
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        $this->setup_hooks();
    }

    /**
     * Set up action hooks.
     *
     * @since 1.0.0
     * @return void
     */
    protected function setup_hooks()
    {
        add_action('init', [$this, 'register_post_types'], 5);
        // Flush rewrite rules on activation only
        register_activation_hook(__FILE__, [$this, 'flush_rewrite_rules']);
    }

    /**
     * Register custom post types.
     *
     * @since 1.0.0
     * @return void
     */
    public function register_post_types()
    {
        if (!is_blog_installed()) {
            return;
        }

        $custom_post_types = self::get_post_type_args();

        foreach ($custom_post_types as $post_type => $args) {
            if (post_type_exists($post_type)) {
                continue;
            }

            $labels = $this->get_post_type_labels(
                $args['singular_name'],
                $args['general_name'],
                $args['menu_name']
            );

            $post_type_args = [
                'label'               => esc_html__($args['singular_name'], 'product-review-manager'),
                'description'         => esc_html__($args['singular_name'] . ' Post Type', 'product-review-manager'),
                'labels'              => $labels,
                'supports'            => $args['supports'],
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => $args['show_in_menu'],
                'show_in_rest'        => true,
                'menu_icon'           => $args['dashicon'],
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => $args['show_in_nav_menus'],
                'can_export'          => true,
                'has_archive'         => $args['has_archive'],
                'exclude_from_search' => $args['exclude_from_search'],
                'publicly_queryable'  => true,
                'capability_type'     => $args['capability_type'],
                'rewrite'             => [
                    'slug'       => 'product-reviews', // Changed to a simpler slug
                    'with_front' => false,
                    'pages'      => true,
                    'feeds'      => true,
                ],
            ];

            $result = register_post_type($post_type, $post_type_args);
            if (is_wp_error($result) && defined('WP_DEBUG') && WP_DEBUG) {
                error_log(sprintf('Product Review Manager: Failed to register post type %s: %s', $post_type, $result->get_error_message()));
            }
        }
    }

    /**
     * Get labels for a custom post type.
     *
     * @since 1.0.0
     * @param string $singular_name Singular name of the post type.
     * @param string $general_name General name of the post type.
     * @param string $menu_name Menu name for the post type.
     * @return array Array of labels for the post type.
     */
    private function get_post_type_labels($singular_name, $general_name, $menu_name)
    {
        return [
            'name'                  => esc_html__($general_name, 'product-review-manager'),
            'singular_name'         => esc_html__($singular_name, 'product-review-manager'),
            'menu_name'             => esc_html__($menu_name, 'product-review-manager'),
            'name_admin_bar'        => esc_html__($singular_name, 'product-review-manager'),
            'archives'              => esc_html__($singular_name . ' Archives', 'product-review-manager'),
            'attributes'            => esc_html__($singular_name . ' Attributes', 'product-review-manager'),
            'parent_item_colon'     => esc_html__('Parent ' . $singular_name . ':', 'product-review-manager'),
            'all_items'             => esc_html__($general_name, 'product-review-manager'),
            'add_new_item'          => esc_html__('Add ' . $singular_name, 'product-review-manager'),
            'add_new'               => esc_html__('Add', 'product-review-manager'),
            'new_item'              => esc_html__('New ' . $singular_name, 'product-review-manager'),
            'edit_item'             => esc_html__('Edit ' . $singular_name, 'product-review-manager'),
            'update_item'           => esc_html__('Update ' . $singular_name, 'product-review-manager'),
            'view_item'             => esc_html__('View ' . $singular_name, 'product-review-manager'),
            'view_items'            => esc_html__('View ' . $general_name, 'product-review-manager'),
            'search_items'          => esc_html__('Search ' . $singular_name, 'product-review-manager'),
            'not_found'             => esc_html__('Not found', 'product-review-manager'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'product-review-manager'),
            'featured_image'        => esc_html__('Featured Image', 'product-review-manager'),
            'set_featured_image'    => esc_html__('Set featured image', 'product-review-manager'),
            'remove_featured_image' => esc_html__('Remove featured image', 'product-review-manager'),
            'use_featured_image'    => esc_html__('Use as featured image', 'product-review-manager'),
            'insert_into_item'      => esc_html__('Insert into ' . $singular_name, 'product-review-manager'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this ' . $singular_name, 'product-review-manager'),
            'items_list'            => esc_html__($general_name . ' list', 'product-review-manager'),
            'items_list_navigation' => esc_html__($general_name . ' list navigation', 'product-review-manager'),
            'filter_items_list'     => esc_html__('Filter ' . $general_name . ' list', 'product-review-manager'),
        ];
    }

    /**
     * Flush rewrite rules.
     *
     * Called on plugin/theme activation to update permalinks.
     *
     * @since 1.0.0
     * @return void
     */
    public static function flush_rewrite_rules()
    {
        flush_rewrite_rules();
    }

    /**
     * Get custom post type arguments.
     *
     * @since 1.0.0
     * @return array Array of post type arguments.
     */
    public static function get_post_type_args()
    {
        return [
            'product_review' => [
                'menu_name'           => esc_html__('Product Reviews', 'product-review-manager'),
                'singular_name'       => esc_html__('Product Review', 'product-review-manager'),
                'general_name'        => esc_html__('Product Reviews', 'product-review-manager'),
                'dashicon'            => 'dashicons-star-filled',
                'has_archive'         => true,
                'exclude_from_search' => false,
                'show_in_nav_menus'   => false,
                'show_in_menu'        => true,
                'capability_type'     => 'post',
                'supports'            => ['title', 'editor', 'revisions', 'thumbnail', 'custom-fields'],
            ],
        ];
    }
}