<?php
/**
 * ApplePay
 *
 * @package     GSA
 * @since       1.0.0
 * @author      Tanner Moushey
 */

namespace GSA;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ApplePay
 */
class ApplePay extends \Give_Stripe_Gateway {

	public function __construct() {

		parent::__construct();

		//Check if Apple Pay is enabled.
		if ( ! give_is_gateway_active( 'stripe_apple_pay' ) ) {
			return;
		}

		//Remove CC fieldset.
		add_action( 'give_stripe_apple_pay_cc_form', '__return_false' );
		add_action( 'give_pre_form', array( $this, 'form_js' ) );

		add_action( 'give_gateway_stripe_apple_pay', array( $this, 'process_payment' ) );

	}

	public function form_js() {

		?>
		<script>
          jQuery(document).ready(function ($) {

            if (undefined === Stripe) {
              return;
            }

            Stripe.applePay.checkAvailability(function (available) {
              if (available) {
                jQuery('.give-gateway-stripe_apple_pay-cont').show();
              } else {
                $('.give-gateway-paypal-cont').css('width', '100%');
              }
            });

            var $body = $('body');
            var $form = $('form[id^=give-form]');

            $body.on('submit', '.give-form', function (e) {

              //Form that has been submitted.
              var $form = $(this);
              var $form_submit = $(this).find('.give-submit-button-wrap input[type="submit"]');

              //Check that Stripe is indeed the gateway chosen.
              var chosen_gateway = $form.find('input[name="give-gateway"]').val();
              if (chosen_gateway === 'stripe_apple_pay') {
                debugger;
                $form_submit.prop('disabled', true);
                return false;
              }

            }).on('click', '.give-submit', function (e) {

              //Form that has been submitted.
              var $form = $(this).parents('form');
              var $form_submit = $form.find('.give-submit-button-wrap input[type="submit"]');

              //Check that Stripe is indeed the gateway chosen.
              var chosen_gateway = $form.find('input[name="give-gateway"]').val();
              if (chosen_gateway !== 'stripe_apple_pay') {
                return;
              }

              e.preventDefault();
              e.stopPropagation();

              $form_submit.prop('disabled', true);

              var paymentRequest = {
                countryCode : 'US',
                currencyCode: 'USD',
                total       : {
                  label : '<?php echo get_option( 'blogname' ); ?>',
                  amount: $form.find('input[name=give-amount]').val()
                }
              };

              var session = Stripe.applePay.buildSession(paymentRequest, function (result, completion) {

                console.log(result);
                console.log(completion);

                // token contains id, last4, and card type
                var token = result.token.id;

                // insert the token into the form so it gets submitted to the server
                $form.append('<input type=\'hidden\' name=\'give_stripe_token\' value=\'' + token + '\' />');

                // and submit
                $form.get(0).submit();

                completion(ApplePaySession.STATUS_SUCCESS);

              }, function (sessionError) {

                // re-enable the submit button
                $form_submit.attr('disabled', false);

                // Hide the loading animation
                jQuery('.give-loading-animation').fadeOut();

                //the error
                var error = '<div class="give_errors"><p class="give_error">' + response.error.message + '</p></div>';

                // show the errors on the form
                $form.find('[id^=give-stripe-payment-errors]').html(error);

                // re-add original submit button text
                if (give_global_vars.complete_purchase) {
                  $form_submit.val(give_global_vars.complete_purchase);
                } else {
                  $form_submit.val('Donate Now');
                }
                completion(ApplePaySession.STATUS_FAILURE);

              });

              session.oncancel = function (x) {
                console.log('User hit the cancel button in the payment window');
                $form_submit.attr('disabled', false);
              };

              session.begin();
            });

          });
		</script>
		<?php
	}

}