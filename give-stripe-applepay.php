<?php

/**
 * Plugin Name: Give - Stripe Apple Pay
 * Plugin URI:  https://givewp.com/addons
 * Description: Adds Apple Pay to the Stripe payment Gateway
 * Version:     1.0.0
 * Author:      iWitness Design
 * Author URI:  https://iwitnessdesign.com
 * Text Domain: give-stripe
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define constants.
 *
 * Required minimum versions, paths, urls, etc.
 */
if ( ! defined( 'GSA_VERSION' ) ) {
	define( 'GSA_VERSION', '1.0.0' );
}
if ( ! defined( 'GSA_PLUGIN_FILE' ) ) {
	define( 'GSA_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'GSA_PLUGIN_DIR' ) ) {
	define( 'GSA_PLUGIN_DIR', dirname( GSA_PLUGIN_FILE ) );
}
if ( ! defined( 'GSA_PLUGIN_URL' ) ) {
	define( 'GSA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'GSA_BASENAME' ) ) {
	define( 'GSA_BASENAME', plugin_basename( __FILE__ ) );
}

class GSA {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the GSA
	 *
	 * @return GSA
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof GSA ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		require_once GSA_PLUGIN_DIR . 'vendor/autoload.php';

		GSA\Admin::get_instance();

		add_action( 'plugins_loaded', function() {
			if ( class_exists( 'Give_Stripe_Gateway' ) ) {
				new GSA\ApplePay();
			}
		}, 20 );
	}

}