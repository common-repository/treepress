<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.blackandwhitedigital.eu/
 * @since             1.0.0
 * @package           Treepress
 *
 * @wordpress-plugin
 * Plugin Name: TreePress
 * Plugin URI:        http://wordpress.org/plugins/treepress
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           3.0.2
 * Author:            Black and White Digital Ltd
 * Author URI:        https://www.blackandwhitedigital.eu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       treepress
 * Domain Path:       /languages
 */

if ( !function_exists( 'tre_fs' ) ) {
    // Create a helper function for easy SDK access.
    function tre_fs()
    {
        global  $tre_fs ;
        
        if ( !isset( $tre_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_3211_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_3211_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $tre_fs = fs_dynamic_init( array(
                'id'              => '3211',
                'slug'            => 'treepress',
                'type'            => 'plugin',
                'public_key'      => 'pk_0c206c382b10b68f1f34477d24e1c',
                'is_premium'      => false,
                'premium_suffix'  => 'Premium',
                'has_addons'      => true,
                'has_paid_plans'  => true,
                'has_affiliation' => 'all',
                'menu'            => array(
                'slug'       => 'treepress-account-settings',
                'first-path' => 'admin.php?page=treepress-account-settings',
                'parent'     => array(
                'slug' => 'treepress',
            ),
            ),
                'is_live'         => true,
            ) );
        }
        
        return $tre_fs;
    }
    
    // Init Freemius.
    tre_fs();
    // Signal that SDK was initiated.
    do_action( 'tre_fs_loaded' );
}

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TREEPRESS_VERSION', '3.0.2' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-treepress-activator.php
 */
function activate_treepress()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-treepress-activator.php';
    Treepress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-treepress-deactivator.php
 */
function deactivate_treepress()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-treepress-deactivator.php';
    Treepress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_treepress' );
register_deactivation_hook( __FILE__, 'deactivate_treepress' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-treepress.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_treepress()
{
    $plugin = new Treepress();
    $plugin->run();
}

run_treepress();