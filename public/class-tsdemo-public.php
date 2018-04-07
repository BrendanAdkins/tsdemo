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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tsdemo-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// TODO change time back to this->version
		wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tsdemo-public.js', array( 'jquery' ), time(), false);
		wp_enqueue_script('stripe_checkout', "https://checkout.stripe.com/checkout.js");
		$stripeSettingsData = array(
		    'stripe_api_key'	=> "pk_test_X74EJiAxEY7uIPKWQCp18PEb",
		    'stripe_site_name'	=> "Your Site",
		    'stripe_amount_options'	=> [5,25,35,55,100]
		);
		wp_localize_script('stripe_checkout', 'php_vars', $stripeSettingsData);
	}
	
	/**
	 * Parse out the donation form shortcode and replace it with the form template.
	 *
	 * @since 1.0.0
	 */
	public static function render_donation_form($atts, $content = "") {
		
/*
		$my_post = array(

		  'post_status'	=> 'publish',
		  'post_title' => 'donation test',
		  'post_type' => 'tsdemo_donation',
		  'meta_input'	=> array(
		  	'_tsdemo_don_amt' => '2.00',
		  	'_tsdemo_donor_name' => 'Generous Donor Failure',
		  	'_tsdemo_don_status' => 'failed'
		  	)
		);
		
		$result = wp_insert_post($my_post, true);
		print_r($result);
*/
// 		include(plugin_dir_path( __FILE__ ). 'partials/form.php');
// 		return file_get_contents(plugin_dir_path( __FILE__ ). 'partials/form.html');	

		ob_start();
		if (is_feed()) {
			include(plugin_dir_path( __FILE__ ). 'partials/feed_form.php');
		} else {
			echo file_get_contents(plugin_dir_path( __FILE__ ). 'partials/form.html');
		}
		return ob_get_clean();
	}

}
