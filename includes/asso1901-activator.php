<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Asso1901
 * @subpackage Asso1901/includes
 * @author     Loïc Carney <elnox04@gmail.com>
 */
class Asso1901_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		include_once plugin_dir_path( __FILE__ ).'asso1901-db-schema.php';
		$schema = new Asso1901_DbSchema();

		$schema->create();

	}

}
