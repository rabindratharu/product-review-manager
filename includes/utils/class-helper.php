<?php

/**
 * Plugin Utils
 *
 * @package product-review-manager
 * @since 1.0.0
 */

namespace Product_Review_Manager\Utils;

use Product_Review_Manager\Utils\Singleton;

/**
 * Helper class.
 *
 * Handles utility functions for the current theme/plugin.
 *
 * @since 1.0.0
 */
class Helper
{
    use Singleton;

    /**
     * Get an array of posts.
     *
     * @static
     * @access public
     * @param array $args Define arguments for the get_posts function.
     * @return array
     */
    public static function get_posts($args)
    {
        if (is_string($args)) {
            $args = add_query_arg(
                [
                    'suppress_filters' => false,
                ]
            );
        } elseif (is_array($args) && ! isset($args['suppress_filters'])) {
            $args['suppress_filters'] = false;
        }

        // Get the posts.
        // TODO: WordPress.VIP.RestrictedFunctions.get_posts_get_posts.
        $posts = get_posts($args);

        // Properly format the array.
        $items = [];
        foreach ($posts as $post) {
            $items[$post->ID] = $post->post_title;
        }
        wp_reset_postdata();

        return $items;
    }
}
