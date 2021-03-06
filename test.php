<?php

/**
 * Test
 *
 * This plugin was generated using Mickey Kay's wp-plugin grunt-init
 * template: https://github.com/MickeyKay/wp-plugin
 *
 * @link              http://wordpress.org/plugins/test
 * @since             1.0.0
 * @package           Test
 *
 * @wordpress-plugin
 * Plugin Name:       Test
 * Plugin URI:        http://wordpress.org/plugins/test
 * Description:       The best WordPress extension ever made!
 * Version:           1.0.0
 * Author:            Mickey Kay
 * Author URI:        http://mickeykaycreative.com?utm_source=test&utm_medium=plugin-repo&utm_campaign=WordPress%20Plugins/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       test
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

register_activation_hook( __FILE__, 'activate_test' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-test-activator.php
 */
function activate_test() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-test-activator.php';
	Test_Activator::activate();
}

register_deactivation_hook( __FILE__, 'deactivate_test' );
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-test-deactivator.php
 */
function deactivate_test() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-test-deactivator.php';
	Test_Deactivator::deactivate();
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-test.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_test() {

	// Pass main plugin file through to plugin class for later use.
	$args = array(
		'plugin_file' => __FILE__,
	);

	$plugin = Test::get_instance( $args );
	$plugin->run();

}
run_test();
