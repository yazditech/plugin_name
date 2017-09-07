<?php
/**
 * plugin-name
 *
 * @author      Mahdi Yazdi
 * @copyright   2016 Green Web
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: plugin-name
 * Plugin URI:  http://mahdiyazdi.com
 * Description:
 * Version:     1.0.0
 * Author:      Mahdi Yazdi
 * Author URI:  http://mahdiyazdi.com
 * Text Domain: plugin-name
 * License:     Green Web
 * License URI: http://www.mahdiyazdi.com/licenses/
 */

// Limit Direct Access.
defined('ABSPATH') || exit('no access');

final class GWC_Yazdi {
    private static $_instance = null;

    public static function getInstance() {
        if( self::$_instance === null ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {

        // Define Constants
        define('GWC_PATH', trailingslashit(plugin_dir_path(__FILE__)));
        define('GWC_URL', trailingslashit(plugin_dir_url(__FILE__)));
        define('GWC_INC', trailingslashit(GWC_PATH . 'inc'));
        define('GWC_CLASS_PATH', trailingslashit(GWC_PATH . 'inc/classes'));
        define('GWC_TPL', trailingslashit(GWC_PATH . 'tpl'));
        define('GWC_CSS', trailingslashit(GWC_URL . 'assets/css'));
        define('GWC_JS', trailingslashit(GWC_PATH . 'assets/js'));
        define('GWC_IMAGES', trailingslashit(GWC_URL . 'assets' . '/' . 'img'));
        define('GWC_ADMIN_CSS', trailingslashit(GWC_URL . 'admin/assets/css'));
        define('GWC_ADMIN_JS', trailingslashit(GWC_URL . 'admin/assets/js'));

        define('GWC_NAME', 'plugin-name');
        define('GWC_VERSION', '1.0.0');

        spl_autoload_register( array ( $this, '__autoload' ) );

        ################## Admin Operations
        if (is_admin()) {

            $obj_gwp_admin = new GWC_Admin(GWC_NAME, GWC_VERSION);

        }
        ################## FrontEnd Operations
        else {

            $obj_gwp_frontend = new GWC_Frontend(GWC_NAME, GWC_VERSION);
        }

        ################## Load in Both (admin & frontend)

        // Create Default Settings on Activate
        $obj_gwp_activator = new GWC_Activator();
        register_activation_hook(__FILE__, array($obj_gwp_activator, 'activate'));

        // Remove and  disable settings & ...
        $obj_gwp_deactivator = new GWC_Deactivator();
        register_deactivation_hook(__FILE__, array($obj_gwp_deactivator, 'deactivate'));

        $obj_gwp_loader = new GWC_Loader(GWC_NAME);
        $obj_gwp_public = new GWC_Public(GWC_NAME, GWC_VERSION);



    }

    public function __autoload( $class )
    {
        if ( FALSE !== strpos( $class, 'GWC_' ) ) {
            $class_name = 'class-' . str_replace( '_', '-', $class );
            $class_file_path = GWC_CLASS_PATH . strtolower( $class_name ) . '.php';
            if ( is_file( $class_file_path ) && file_exists( $class_file_path ) ) {
                include_once $class_file_path;
            }
        }
    }

    public function __clone() {

        // TODO: Implement __clone() method.
    }

    public function __wakeup() {

        // TODO: Implement __wakeup() method.
    }
}

GWC_Yazdi::getInstance();
