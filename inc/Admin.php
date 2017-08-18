<?php

namespace GSA;

class Admin {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the Admin
	 *
	 * @return Admin
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof Admin ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function __construct() {
		$this->hooks();
	}

	protected function hooks() {
		add_filter( 'give_payment_gateways', array( $this, 'register_gateway' ) );
	}

	/**
	 * Register the Stripe payment gateways.
	 *
	 * @access      public
	 * @since       1.0
	 *
	 * @param $gateways array
	 *
	 * @return array
	 */
	public function register_gateway( $gateways ) {

		// Format: ID => Name
		$gateways['stripe_apple_pay']     = array(
			'admin_label'    => esc_html__( 'Stripe - Apple Pay', 'give-stripe-applepay' ),
			'checkout_label' => esc_html__( 'Apple Pay', 'give-stripe-applepay' ),
		);

		return $gateways;
	}

}