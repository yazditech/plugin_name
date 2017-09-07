<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://mahdiyazdi.com
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Mahdi Yazdi <info@mahdiyazdi.com>
 */

class GWC_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu',  array( $this, 'gwp_add_admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_styles' ) );
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function load_scripts_styles() {

		/**
		 * This function is provided for loading scripts only.
		 **
		 * These scripts that loaded in this section wil be loaded
		 * only in the admin area of the Wordpress
		 */

		wp_enqueue_style(
							'gwp-admin.css',
							GWC_URL . 'admin/assets/css/pl-admin.css',
							array(),
							$this->version,
							'all'
		);

		wp_enqueue_script(
							'gwp-admin.js',
							GWC_URL . 'admin/assets/js/pl-admin.js',
							array( 'jquery' ),
							$this->version,
							false
		);

	}

	public function gwp_add_admin_menus() {

        ############## Admin Menu
        add_menu_page(
            'منوی پلاگین',
            'منوی پلاگین',
            'gwcm_admin_cap', // Capability
            'gwp_main_menu', // Menu Slug
            array( $this, 'gwp_panel' ), // Callable $function
            'dashicons-format-status', // Icon Url
            2 // Position
        );
        add_submenu_page(
            'gwp_main_menu', //parent_slug
            'منوی یک', //page_title
            'منوی یک', //menu_title
            'gwcm_admin_cap', //capability
            'gwp_panel', //menu_slug
            array( $this, 'gwp_panel' ) //callable $function
        );
        add_submenu_page(
            'gwp_main_menu', //parent_slug
            'منوی دو', //page_title
            'منوی دو', //menu_title
            'gwcm_admin_cap', //capability
            'gwp_panel_2', //menu_slug
            array( $this, 'gwp_panel_2' ) //callable $function
        );

        ############## Admin Menu
	// Capability Name: gwcm_admin_user

	}

    //Plugin option page operations
    public static function gwp_panel() {

        include GWC_PATH . '/tpl/panel.php';
    }

    //Plugin option page operations
    public static function gwp_panel_2() {

        include GWC_PATH . '/tpl/panel.php';
    }
}
