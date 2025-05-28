<?php

/**
 * Register Meta Boxes
 *
 * @package product-review-manager
 * @since 1.0.0
 */

namespace Product_Review_Manager;

use Product_Review_Manager\Utils\Singleton;
use Product_Review_Manager\Utils\Helper;

/**
 * Register meta boxes class.
 *
 * Handles registration of custom meta boxes for product reviews.
 *
 * @since 1.0.0
 */
class Meta_Boxes
{
    use Singleton;

    /**
     * Meta field keys
     */
    const PRODUCT_NAME_FIELD = 'prm_product_name';
    const RATING_FIELD = 'prm_rating';
    const REVIEWER_NAME_FIELD = 'prm_reviewer_name';
    const NONCE_FIELD = 'prm_meta_box_nonce';

    /**
     * Private constructor to prevent direct object creation.
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
    protected function setup_hooks(): void
    {
        add_action('add_meta_boxes', [$this, 'add_custom_meta_box']);
        add_action('save_post', [$this, 'save_post_meta_data']);
    }

    /**
     * Add custom meta box for product reviews.
     *
     * @return void
     */
    public function add_custom_meta_box(): void
    {
        $screens = ['product_review'];

        foreach ($screens as $screen) {
            add_meta_box(
                'product_review_meta_box',
                esc_html__('Review Details', 'product-review-manager'),
                [$this, 'render_meta_box_content'],
                $screen,
                'normal',
                'high'
            );
        }
    }

    /**
     * Render meta box content.
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function render_meta_box_content(\WP_Post $post): void
    {
        // Get current values
        $product_name = get_post_meta($post->ID, self::PRODUCT_NAME_FIELD, true);
        $rating = get_post_meta($post->ID, self::RATING_FIELD, true);
        $reviewer_name = get_post_meta($post->ID, self::REVIEWER_NAME_FIELD, true);

        // Get products for dropdown
        $products = Helper::get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        // Security field
        wp_nonce_field(
            plugin_basename(__FILE__),
            self::NONCE_FIELD
        );
?>
        <div class="prm-meta-box-container">
            <!-- Product Selection -->
            <div class="prm-meta-box-field">
                <label for="<?php echo esc_attr(self::PRODUCT_NAME_FIELD); ?>">
                    <?php esc_html_e('Product Name', 'product-review-manager'); ?>
                </label>
                <?php if (!empty($products)) : ?>
                    <select name="<?php echo esc_attr(self::PRODUCT_NAME_FIELD); ?>"
                        id="<?php echo esc_attr(self::PRODUCT_NAME_FIELD); ?>" class="prm-select-field">
                        <option value="">
                            <?php esc_html_e('Select a Product', 'product-review-manager'); ?>
                        </option>
                        <?php foreach ($products as $product_id => $product_title) : ?>
                            <option value="<?php echo esc_attr($product_id); ?>" <?php selected($product_name, $product_id); ?>>
                                <?php echo esc_html($product_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else : ?>
                    <p class="prm-no-products">
                        <?php esc_html_e('No products found', 'product-review-manager'); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Rating Selection -->
            <div class="prm-meta-box-field">
                <label for="<?php echo esc_attr(self::RATING_FIELD); ?>">
                    <?php esc_html_e('Rating (1-5)', 'product-review-manager'); ?>
                </label>
                <select name="<?php echo esc_attr(self::RATING_FIELD); ?>" id="<?php echo esc_attr(self::RATING_FIELD); ?>"
                    class="prm-select-field">
                    <option value="">
                        <?php esc_html_e('Select Rating', 'product-review-manager'); ?>
                    </option>
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <option value="<?php echo esc_attr($i); ?>" <?php selected($rating, $i); ?>>
                            <?php
                            printf(
                                esc_html__('%d Star%s', 'product-review-manager'),
                                $i,
                                $i > 1 ? 's' : ''
                            );
                            ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Reviewer Name -->
            <div class="prm-meta-box-field">
                <label for="<?php echo esc_attr(self::REVIEWER_NAME_FIELD); ?>">
                    <?php esc_html_e('Reviewer\'s Name', 'product-review-manager'); ?>
                </label>
                <input type="text" name="<?php echo esc_attr(self::REVIEWER_NAME_FIELD); ?>"
                    id="<?php echo esc_attr(self::REVIEWER_NAME_FIELD); ?>" value="<?php echo esc_attr($reviewer_name); ?>"
                    class="prm-text-field">
            </div>
        </div>
<?php
    }

    /**
     * Save post meta data when the post is saved.
     *
     * @param int $post_id Post ID.
     * @return void
     */
    public function save_post_meta_data(int $post_id): void
    {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Verify nonce
        if (
            !isset($_POST[self::NONCE_FIELD]) ||
            !wp_verify_nonce(
                sanitize_text_field(wp_unslash($_POST[self::NONCE_FIELD])),
                plugin_basename(__FILE__)
            )
        ) {
            return;
        }

        // Check user capabilities
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save Product Name
        if (isset($_POST[self::PRODUCT_NAME_FIELD])) {
            $product_id = absint($_POST[self::PRODUCT_NAME_FIELD]);
            update_post_meta(
                $post_id,
                self::PRODUCT_NAME_FIELD,
                $product_id
            );
        }

        // Save Rating (1-5 only)
        if (isset($_POST[self::RATING_FIELD])) {
            $rating = min(max(absint($_POST[self::RATING_FIELD]), 1), 5);
            update_post_meta(
                $post_id,
                self::RATING_FIELD,
                $rating ?: ''
            );
        }

        // Save Reviewer's Name
        if (isset($_POST[self::REVIEWER_NAME_FIELD])) {
            $reviewer_name = sanitize_text_field(
                wp_unslash($_POST[self::REVIEWER_NAME_FIELD])
            );
            update_post_meta(
                $post_id,
                self::REVIEWER_NAME_FIELD,
                $reviewer_name
            );
        }
    }
}
