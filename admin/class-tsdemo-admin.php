<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://brendanadkins.com/
 * @since      1.0.0
 *
 * @package    Tsdemo
 * @subpackage Tsdemo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tsdemo
 * @subpackage Tsdemo/admin
 * @author     Brendan Adkins <b@brendanadkins.com>
 */
class Tsdemo_Admin {

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

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tsdemo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tsdemo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tsdemo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tsdemo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tsdemo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tsdemo-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Registers a custom post type for recording donation transactions.
	 * Static since this is called during init.
	 *
	 * @since 1.0.0
	 */

	public static function new_tsdemo_donation() {
		
		$custom_name = 'tsdemo_donation';
		
		$opts = array();
		$opts['can_export'] = TRUE;
		$opts['capability_type'] = 'post';
		$opts['description'] = '';
		$opts['exclude_from_search'] = TRUE;
		$opts['has_archive'] = FALSE;
		$opts['hierarchical'] = FALSE;
		$opts['map_meta_cap'] = TRUE;
		$opts['menu_icon'] = '';
		$opts['public'] = FALSE;
		$opts['publicly_queryable'] = FALSE;
		$opts['rewrite'] = FALSE;
		$opts['show_in_admin_bar'] = FALSE;
		$opts['show_in_menu'] = FALSE;
		$opts['show_in_nav_menu'] = FALSE;
		
		register_post_type($custom_name, $opts);
	 }
	
	/**
	 * Registers metadata keys for custom post.
	 * Static since this is called during init.
	 *
	 * @since 1.0.0
	 */

	public static function new_tsdemo_donation_meta() {
		$amt_name = "_tsdemo_don_amt";
		$amt_opts = array();
		$amt_opts["type"] = "number";
		$amt_opts["description"] = "The amount of a donation made through the TSDemo form";
		$amt_opts["single"] = TRUE;
		$amt_opts["show_in_rest"] = FALSE;
		register_meta('post', $amt_name, $amt_opts);
		
		$status_name = "_tsdemo_don_status";
		$status_opts = array();
		$status_opts["type"] = "string";
		$status_opts["description"] = "The status of a donation made through the TSDemo form";
		$status_opts["single"] = TRUE;
		$status_opts["show_in_rest"] = FALSE;
		register_meta('post', $status_name, $status_opts);
		
		$donor_name = "_tsdemo_donor_name";
		$donor_opts = array();
		$donor_opts["type"] = "string";
		$donor_opts["description"] = "The name of a donor using the TSDemo form";
		$donor_opts["single"] = TRUE;
		$donor_opts["show_in_rest"] = FALSE;
		register_meta('post', $donor_name, $donor_opts);
		
		$id_name = "_tsdemo_transact_id";
		$id_opts = array();
		$id_opts["type"] = "string";
		$id_opts["description"] = "The token ID returned from a Stripe transaction";
		$id_opts["single"] = TRUE;
		$id_opts["show_in_rest"] = FALSE;
		register_meta('post', $id_name, $id_opts);
	}
}
