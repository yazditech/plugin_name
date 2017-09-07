<?php
/**
 * Fired during plugin loading.
 *
 * This class defines all code necessary in init and plugins_loaded add_actions
 *
 * @link       http://mahdiyazdi.com
 * @since      1.0.0
 *
 * @package    plugin-name
 * @subpackage plugin-name/inc
 * @author     Mahdi Yazdi <info@mahdiyazdi.com>
 */
class GWC_Loader {

	private $plugin_name;

	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;
		add_action('plugins_loaded', array( $this, 'pl_load_textdomain' ) );
		add_action('init', array( $this, 'pl_init_callback' ) );

	}
	public function pl_load_textdomain() {

		load_plugin_textdomain( $this->plugin_name, false, $this->plugin_name . '/lang' );
	}

	public function pl_init_callback() {


	}
}
