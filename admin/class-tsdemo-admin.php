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
	 * Prepended to option names to distinguish them within WordPress.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		string	$option_prefix	The prefix for plugin settings.
	 */
	private $option_prefix;

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
		$this->option_prefix = TS_DEMO_OPTION_PREFIX;
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
		 // TODO fix
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tsdemo-admin.css', array(), time(), 'all' );

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
		
		$donor_email = "_tsdemo_donor_email";
		$donor_opts = array();
		$donor_opts["type"] = "string";
		$donor_opts["description"] = "The email of a donor using the TSDemo form";
		$donor_opts["single"] = TRUE;
		$donor_opts["show_in_rest"] = FALSE;
		register_meta('post', $donor_email, $donor_opts);
		
		$id_name = "_tsdemo_transact_id";
		$id_opts = array();
		$id_opts["type"] = "string";
		$id_opts["description"] = "The token ID returned from a Stripe transaction";
		$id_opts["single"] = TRUE;
		$id_opts["show_in_rest"] = FALSE;
		register_meta('post', $id_name, $id_opts);
	}
	
	/**
	 * Registers a options page for the admin dashboard.
	 *
	 * @since 1.0.0
	 */
	 
	public function add_tsdemo_options_page() {
		$this->plugin_screen_hook_suffix = add_options_page(
			'TSDemo Settings',
			'TSDemo Donation Form',
			'manage_options',
			$this->plugin_name,
			array($this, 'display_options_page')
		);
	}
	
	/**
	 * Callback for the options page hook, which picks the (empty) settings template out of the partials folder.
	 *
	 * @since 1.0.0
	 */
	
	public function display_options_page() {
		include_once 'partials/tsdemo-admin-display.php';
	}
	
	/**
	 * Registers a sidebar tools menu page for the admin dashboard.
	 * 
	 * @since 1.0.0
	 */
	 
	public function add_tsdemo_menu_page() {
		$this->plugin_menu_hook_suffix = add_management_page(
			'TSDemo Donation Records',
			'TSDemo Donations',
			'manage_options',
			$this->plugin_name,
			array($this, 'display_tools_page')
		);
	}
	
	/**
	 * Callback for the tools menu page, which picks its template out of the partials folder.
	 *
	 * @since 1.0.0
	 */
	
	public function display_tools_page() {
		include_once 'partials/tsdemo-admin-tools.php';
	}
	
	/**
	 * Specifies which options fields to register with WordPress.
	 *
	 * @since 1.0.0
	 */
	 
	public function register_tsdemo_settings() {
		register_setting(
			$this->plugin_name,
			$this->option_prefix.'_stripe_api_key'
		);
		
		register_setting(
			$this->plugin_name,
			$this->option_prefix.'_stripe_secret_key'
		);
			
		register_setting(
			$this->plugin_name,
			$this->option_prefix.'_donation_amounts'
		);
		
		add_settings_section(
			$this->option_prefix.'_general',
			"Donation Settings",
			array($this, 'register_options_section_callback'),
			$this->plugin_name
		);
		
		add_settings_field(
			$this->option_prefix.'_stripe_api_key',
			"Stripe Publishable API Key",
			array($this, 'add_option_stripe_api_callback'),
			$this->plugin_name,
			$this->option_prefix.'_general',
			array('label_for' => $this->option_prefix.'_stripe_api_key')
		);
		
		add_settings_field(
			$this->option_prefix.'_stripe_secret_key',
			"Stripe Secret Key",
			array($this, 'add_option_stripe_secret_callback'),
			$this->plugin_name,
			$this->option_prefix.'_general',
			array('label_for' => $this->option_prefix.'_stripe_secret_key')
		);
		
		add_settings_field(
			$this->option_prefix.'_donation_amounts',
			"Default Donation Amounts",
			array($this, 'add_option_donation_amounts_callback'),
			$this->plugin_name,
			$this->option_prefix.'_general',
			array('label_for' => $this->option_prefix.'_donation_amounts')
		);
	}
	
	/**
	 * Callback for additional information on the settings section.
	 * 
	 * @since 1.0.0
	 */
	
	public static function register_options_section_callback() {
		echo "";
	}
	
	/**
	 * Callback for additional information on the Stripe public API key setting.
	 * 
	 * @since 1.0.0
	 */
	
	public function add_option_stripe_api_callback() {
		$option_name = $this->option_prefix.'_stripe_api_key';
		$option = get_option($option_name);
		echo '<input type="text" name="'.$option_name.'" value="'.$option.'">';
	}
	
	/**
	 * Callback for additional information on the Stripe secret API key setting.
	 * 
	 * @since 1.0.0
	 */
	
	public function add_option_stripe_secret_callback() {
		$option_name = $this->option_prefix.'_stripe_secret_key';
		$option = get_option($option_name);
		echo '<input type="text" name="'.$option_name.'" value="'.$option.'">';
	}
	
	/**
	 * Callback for additional information on the donation amount list setting.
	 * 
	 * @since 1.0.0
	 */
	
	public function add_option_donation_amounts_callback() {
		$option_name = $this->option_prefix.'_donation_amounts';
		$option = get_option($option_name, TS_DEMO_DEFAULT_PAYMENTS);
		echo '<input type="text" name="'.$option_name.'" value="'.$option.'">';
	}
}
