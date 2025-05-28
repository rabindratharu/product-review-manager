<?php

/**
 * Autoloader file for Product Review Manager plugin.
 *
 * @package product-review-manager
 * @since 1.0.0
 */

namespace Product_Review_Manager\Utils;

/**
 * Autoloader class.
 *
 * Handles automatic loading of plugin classes and traits.
 *
 * @since 1.0.0
 */
class Autoloader
{
    /**
     * Autoload function.
     *
     * Maps namespaces to file paths and includes the required class or trait files.
     *
     * @since 1.0.0
     * @param string $resource The class or trait name including namespace.
     * @return void
     */
    public static function autoload($resource = '')
    {
        $resource = trim($resource, '\\');
        $namespace_root = 'Product_Review_Manager\\';

        // Bail if not our namespace or invalid resource
        if (empty($resource) || strpos($resource, '\\') === false || strpos($resource, $namespace_root) !== 0) {
            return;
        }

        // Remove root namespace
        $resource = str_replace($namespace_root, '', $resource);

        // Convert namespace to path
        $path = explode(
            '\\',
            str_replace('_', '-', strtolower($resource))
        );

        if (empty($path[0])) {
            return;
        }

        $directory = '';
        $file_name = '';
        $is_trait = false;

        // Map namespace to directory structure
        switch ($path[0]) {
            case 'admin':
                $directory = 'includes/admin';
                $file_name = !empty($path[1]) ? sprintf('class-%s', trim(strtolower($path[1]))) : '';
                break;

            case 'utils':
                $directory = 'includes/utils';
                if (!empty($path[1]) && strpos($path[1], 'singleton') !== false) {
                    $file_name = 'trait-singleton';
                    $is_trait = true;
                } else {
                    $file_name = !empty($path[1]) ? sprintf('class-%s', trim(strtolower($path[1]))) : '';
                }
                break;

            case 'widgets':
                $directory = 'includes/widgets';
                $file_name = !empty($path[1]) ? sprintf('class-%s', trim(strtolower($path[1]))) : '';
                break;

            default:
                $directory = 'includes';
                $file_name = sprintf('class-%s', trim(strtolower($path[0])));
                break;
        }

        if (empty($file_name)) {
            return;
        }

        // Construct file path
        $resource_path = sprintf(
            '%s/%s/%s.php',
            untrailingslashit(PRM_PATH),
            $directory,
            $file_name
        );

        // Validate and include file
        $is_valid_file = validate_file($resource_path);

        if (!empty($resource_path) && file_exists($resource_path) && (0 === $is_valid_file || 2 === $is_valid_file)) {
            require_once $resource_path;
        } elseif (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf('Product_Review_Manager: Failed to load %s %s at %s', $is_trait ? 'trait' : 'class', $resource, $resource_path));
        }
    }
}

// Register the autoloader
spl_autoload_register('\Product_Review_Manager\Utils\Autoloader::autoload');