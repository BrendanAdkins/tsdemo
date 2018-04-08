<?php

/**
 * ThinkShout Demo Plugin
 *
 * @link              https://brendanadkins.com/
 * @since             1.0.0
 * @package           Tsdemo
 *
 * @wordpress-plugin
 * Plugin Name:       TSDemo
 * Plugin URI:        https://github.com/BrendanAdkins/tsdemo
 * Description:       A demo plugin that displays a donation form when triggered by a shortcode, processes donations through Stripe, and lists them in the admin panel.
 * Version:           1.0.0
 * Author:            Brendan Adkins
 * Author URI:        https://brendanadkins.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tsdemo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TS_DEMO_VERSION', '1.0.0' );

define('TS_DEMO_DEFAULT_PAYMENTS','5,15,25,55,100');
define('TS_DEMO_OPTION_PREFIX', 'tsdemo');
define('TS_DEMO_STRIPE_PATH', plugin_dir_path( __FILE__ ) . "vendor/stripe/");

define('TS_DEMO_RECORD_TYPE', "tsdemo_donation");
define('TS_DEMO_META_AMOUNT', "_tsdemo_don_amt");
define('TS_DEMO_META_STATUS', "_tsdemo_don_status");
define('TS_DEMO_META_EMAIL', "_tsdemo_donor_email");
define('TS_DEMO_META_TRANSACTION', "_tsdemo_transact_id");
define('TS_DEMO_NONCE_KEY', "tsdemo_stripe_donation_nonce");
define('TS_DEMO_OPTION_SECRET', '_stripe_secret_key');
define('TS_DEMO_OPTION_KEY', '_stripe_api_key');
define('TS_DEMO_OPTION_AMOUNTS', '_donation_amounts');

/**
 * cf includes/class-tsdemo-activator.php
 */
function activate_tsdemo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tsdemo-activator.php';
	Tsdemo_Activator::activate();
}

/**
 * cf includes/class-tsdemo-deactivator.php
 */
function deactivate_tsdemo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tsdemo-deactivator.php';
	Tsdemo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tsdemo' );
register_deactivation_hook( __FILE__, 'deactivate_tsdemo' );

/**
 * The core plugin class, which defines hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tsdemo.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_tsdemo() {

	$plugin = new Tsdemo();
	$plugin->run();

}
run_tsdemo();
