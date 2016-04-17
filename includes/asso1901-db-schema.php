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
class Asso1901_DbSchema {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function create() {
		require_once(ABSPATH . 'wp-load.php');
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$table_name = $wpdb->prefix . 'asso1901_annee_adhesion';

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		// create the ECPT metabox database table
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE ".$table_name." (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
			  `titre` varchar(20) NOT NULL,
				`date_ag` date NOT NULL,
			  `date_debut` date NOT NULL,
			  `date_fin` date NOT NULL,
			  UNIQUE KEY `id` (`id`),
				UNIQUE `titre` (`titre`)
			);";
			error_log($sql);

			dbDelta($sql);
		}
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function drop() {
		require_once(ABSPATH . 'wp-load.php');
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$table_name = $wpdb->prefix . 'asso1901_annee_adhesion';

		// create the ECPT metabox database table
		$sql = "DROP TABLE IF EXISTS ". $table_name.";";
		error_log($sql);
		$wpdb->query($sql);

	}

}
