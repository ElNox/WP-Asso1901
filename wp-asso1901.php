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

 WP-Asso1901 is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 2 of the License, or
 any later version.

 WP-Asso1901 is distributed in the hope that it will be useful,

 // Clear the permalinks after the post type has been registered
  flush_rewrite_rules();
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with WP-Asso1901. If not, see http://www.gnu.org/licenses/gpl-2.0.txt.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/asso1901-activator.php
 */
function asso1901_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/asso1901-activator.php';
	Asso1901_Activator::activate();

	// Clear the permalinks after the post type has been registered
  flush_rewrite_rules();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/asso1901-deactivator.php
 */
function asso1901_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/asso1901-deactivator.php';
	Asso1901_Deactivator::deactivate();

	// Clear the permalinks to remove our post type's rules
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'asso1901_activate' );
register_deactivation_hook( __FILE__, 'asso1901_deactivate' );

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

if ( is_admin() ) {
     // We are in admin mode
     require_once( dirname(__file__).'/admin/asso1901-admin.php' );
}
