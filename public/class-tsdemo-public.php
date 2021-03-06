<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://brendanadkins.com/
 * @since      1.0.0
 *
 * @package    Tsdemo
 * @subpackage Tsdemo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tsdemo
 * @subpackage Tsdemo/public
 * @author     Brendan Adkins <b@brendanadkins.com>
 */
class Tsdemo_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style($this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tsdemo-public.css', array(), $this->version."0", 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tsdemo-public.js', array( 'jquery' ), $this->version."0", false);
		wp_enqueue_script('stripe_checkout', "https://checkout.stripe.com/checkout.js");
		$stripeSettingsData = array(
		    'stripe_api_key'	=> get_option(TS_DEMO_OPTION_PREFIX.TS_DEMO_OPTION_KEY),
		    'stripe_site_name'	=> get_bloginfo('name'),
		    'stripe_amount_options'	=> explode(",", get_option(TS_DEMO_OPTION_PREFIX.TS_DEMO_OPTION_AMOUNTS)),
		    'stripe_form_nonce' => wp_create_nonce(TS_DEMO_NONCE_KEY),
		    'stripe_form_destination' => admin_url('admin-ajax.php'),
		    'stripe_loader_image' => get_site_url(null, '/wp-admin/images/spinner-2x.gif'),
		    'thank_you_message' => file_get_contents(plugin_dir_path( __FILE__ ). 'partials/thankyou.html'),
		    'uh_oh_message' => file_get_contents(plugin_dir_path( __FILE__ ). 'partials/uhoh.html')
		);
		wp_localize_script('stripe_checkout', 'php_vars', $stripeSettingsData);
	}
	
	/**
	 * Store a flag to ensure that the donation form is only displayed once per page,
	 * since the form elements are intended to have unique IDs.
	 *
	 * @since 1.0.0
	 */
	private static $form_has_rendered = false;
	
	/**
	 * Parse out the donation form shortcode and replace it with the form template.
	 *
	 * @since 1.0.0
	 */
	public static function render_donation_form($atts, $content = "") {
		
		ob_start();
		if (is_feed() || self::$form_has_rendered) {
			include(plugin_dir_path( __FILE__ ). 'partials/donation_link.php');
		} else {
			echo file_get_contents(plugin_dir_path( __FILE__ ). 'partials/form.html');
			self::$form_has_rendered = true;
		}
		return ob_get_clean();
	}
	
	/**
	 * Handle the result of a POST (via ajax) through the donation form once Stripe has processed user data
	 *
	 * @since 1.0.0
	 */
	public function handle_donation_form_post() {
		
		if (!wp_verify_nonce($_REQUEST["wpNonce"], TS_DEMO_NONCE_KEY)) {
			exit("invalid");
		}
		
		$token = $_REQUEST["donationToken"];
		
		global $stripe_options;
		
		// load Stripe libraries
		require_once(TS_DEMO_STRIPE_PATH . '/init.php');
		
		// fetch key from options
		$secret_key = get_option(TS_DEMO_OPTION_PREFIX.TS_DEMO_OPTION_SECRET);	
	   
		$result = array();
		$transaction_id = "";
		
		// make charge attempt
		try {
			\Stripe\Stripe::setApiKey($secret_key);
			$charge = \Stripe\Charge::create(array(
					'amount' => $_REQUEST["donationAmount"],
					'currency' => 'usd',
					'source' => $token
				)
			);
		
			// successful payment
			$result["status"] = "success";
			$transaction_id = $charge["id"];
		} catch (Exception $e) {
			// failed payment
			$result["status"] = "failure";
		}
		
		// Record donation, either successful or failed, as a custom record
		$recorded_amount = $_REQUEST["donationAmount"] / 100;
		$donation_record = array(
			'post_status'	=> 'publish',
			'post_title' => 'donation test',
			'post_type' => TS_DEMO_RECORD_TYPE,
			'meta_input'	=> array(
				TS_DEMO_META_AMOUNT => '$'.$recorded_amount,
				TS_DEMO_META_EMAIL => $_REQUEST['donorEmail'],
				TS_DEMO_META_STATUS => $result["status"],
				TS_DEMO_META_TRANSACTION => $transaction_id
			)
 		);
 		
 		wp_insert_post($donation_record);
		
		// Quick check to make sure this is an async request
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$result = json_encode($result);
			echo $result;
		} else {
			header("Location: ".$_SERVER["HTTP_REFERER"]);
		}
		
		die();
	}

}
