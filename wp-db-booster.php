<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.wpmaniax.com
 * @since             1.0.0
 * @package           Wp_Db_Booster
 *
 * @wordpress-plugin
 * Plugin Name:       WP DB Booster
 * Plugin URI:        http://www.wpmaniax.com/wp-db-booster
 * Description:       WP DB Booster allows you to optimize, clean and analyze your Wordpress database with few clicks. Additionally, it allows you to perform simple security tests and track changes in your database tables. Everything in a modern nice looking interface.
 * Version:           1.0.1
 * Author:            WPManiax
 * Author URI:        http://www.wpmaniax.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-db-booster
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-db-booster-activator.php
 */
function activate_wp_db_booster() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-db-booster-activator.php';
	Wp_Db_Booster_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-db-booster-deactivator.php
 */
function deactivate_wp_db_booster() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-db-booster-deactivator.php';
	Wp_Db_Booster_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_db_booster' );
register_deactivation_hook( __FILE__, 'deactivate_wp_db_booster' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-db-booster.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_db_booster() {

	$plugin = new Wp_Db_Booster();
	$plugin->run();

}
run_wp_db_booster();
