<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.wpmaniax.com
 * @since      1.0.0
 *
 * @package    Wp_Db_Booster
 * @subpackage Wp_Db_Booster/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Db_Booster
 * @subpackage Wp_Db_Booster/includes
 * @author     WPManiax <plugins@wpmaniax.com>
 */
class Wp_Db_Booster_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;

        $arr_tables = array();
        $tables = $wpdb->get_results("SHOW TABLES");
        foreach ($tables as $table) {
            foreach ($table as $t) {
                array_push($arr_tables, $t);
            }
        }
        update_option('wpdbbooster_tables', $arr_tables);

        // Create Log table
        $sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wpdbboost_log` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `plugin_name` varchar(50) DEFAULT NULL,
		  `plugin` varchar(50) DEFAULT NULL,
		  `data` text,
		  `action` enum('activated','deactivated','cleanup') DEFAULT NULL,
		  `updated_at` datetime DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $wpdb->query($sql);
    }

}
