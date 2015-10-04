<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           WP-Asso1901
 *
 * @wordpress-plugin
 * Plugin Name:       WP-Asso1901
 * Plugin URI:        https://github.com/ElNox/WP-Asso1901
 * Description:       Management of members from an assocation or a club.
 * Version:           1.0.0
 * Author:            Loïc Carney
 * Author URI:        http://myworlds.carneyandco.fr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-asso1901
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/asso1901-activator.php
 */
function activate_asso1901() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/asso1901-activator.php';
	Asso1901_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/asso1901-deactivator.php
 */
function deactivate_asso1901() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/asso1901-deactivator.php';
	Asso1901_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_asso1901' );
register_deactivation_hook( __FILE__, 'deactivate_asso1901' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/asso1901.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_asso1901() {

	$plugin = new asso1901();
	$plugin->run();

}
run_asso1901();
