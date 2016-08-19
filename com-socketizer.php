<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://socketizer.com
 * @since             1.0.0
 * @package           Com_Socketizer
 *
 * @wordpress-plugin
 * Plugin Name:       Socketizer
 * Plugin URI:        https://socketizer.com
 * Description:       Your web in real time. Painful Websockets - WordPress integration, no coding skills required!
 * Version:           1.1.0
 * Author:            Stef Kariotidis
 * Author URI:        https://socketizer.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       com-socketizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) exit;

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-com-socketizer-activator.php
 */
function activate_com_socketizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-com-socketizer-activator.php';
	Com_Socketizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-com-socketizer-deactivator.php
 */
function deactivate_com_socketizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-com-socketizer-deactivator.php';
	Com_Socketizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_com_socketizer' );
register_deactivation_hook( __FILE__, 'deactivate_com_socketizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-com-socketizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_com_socketizer() {

	$plugin = new Com_Socketizer();
	$plugin->run();

}
run_com_socketizer();
