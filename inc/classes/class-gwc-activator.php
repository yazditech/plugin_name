<?php

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 * @link       http://mahdiyazdi.com
 * @since      1.0.0
 *
 * @package    plugin-name
 * @subpackage plugin-name/inc
 * @author     Mahdi Yazdi <info@mahdiyazdi.com>
 */
class GWC_Activator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	 // When Plugin Installed this function executed.
	 public function __construct() {

         $this->activate();
         add_action( 'admin_init', array( $this, 'gfms_admin_init' ) );
	 }

	 public function activate() {

		 /**
		  * You can add update_option() or add_option() or create databases
		 **/
         global $wpdb;
         require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

//         $sql_1 = "
//			CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gfms_deputies` (
//			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
//			  `name` varchar(64) DEFAULT NULL,
//			  `file_size_allowed` int(11) DEFAULT NULL,
//			  `is_active` int(11) DEFAULT NULL,
//			  `status` int(11) DEFAULT NULL,
//			  PRIMARY KEY (`id`)
//			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
//
//         $sql_2 = "
//			CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}gfms_deputy_file_ext` (
//			  `id` int(11) NOT NULL AUTO_INCREMENT,
//			  `deputy_id` int(11) DEFAULT NULL,
//			  `estension_allowed` varchar(128) DEFAULT NULL,
//			  PRIMARY KEY (`id`)
//			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
//
//         dbDelta($sql_1);
//         dbDelta($sql_2);
	 }

//    Admin Init on Activate
    public function gfms_admin_init() {

        $role = get_role( 'administrator' );
        $role->add_cap( 'gwp_admin_cap' );
        $role->add_cap( 'gwp_admin_user' );

    }
}