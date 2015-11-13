<?php
/*
Plugin Name: WooCommerce PayGate Gateway
Plugin URI: http://woothemes.com/woocommerce
Description: The <a href="http://www.paygate.co.za" target="_blank">PayGate</a> payment gateway extends WooCommerce by allowing the customer to make payments using either the <a href="http://www.paygate.co.za/payweb.php" target="_blank">PayGate PayWeb</a> or <a href="http://www.paygate.co.za/payxml.php" target="_blank">PayGate PayXML</a> merchant facilities. For any support or bug reporting please open a support ticket with WooThemes.
Version: 1.3.1
Author: Byron Rode
Author URI: http://kidrobot.co.za
*/

/*  Copyright 2015 Byron Rode  (email : byron.rode@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '33851ef28a4040d5621ccc6d375f2587', '18595' );

// Init Plugin after WooCommerce has loaded.
add_action('plugins_loaded', 'woocommerce_init_paygate_gateway', 0);

function woocommerce_init_paygate_gateway(){

	// If the WooCommerce Payment Gateway class is not available, do nothing
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

	class WC_Gateway_Paygate extends WC_Payment_Gateway {

		public function __construct() {
			global $woocommerce;

			$this->id				= 'paygate';
			$this->method_title 	= __( 'PayGate', 'woothemes' );
			$this->has_fields		= false;
			$this->icon				= '';

			$this->init_form_fields();
			$this->init_settings();

			$this->title 			= $this->settings['title'];
			$this->description 		= $this->settings['description'];
			$this->enabled 			= $this->settings['enabled'];
			$this->method			= $this->settings['method'];
			$this->paygate_id	 	= $this->settings['paygate_id'];
			$this->paygate_key		= $this->settings['paygate_key'];
			$this->testmode 		= $this->settings['testmode'];
			$this->enable_diners	= $this->settings['enable_diners'];
			$this->enable_amex		= $this->settings['enable_amex'];
			$this->show_description	= $this->settings['show_description'];
			
			$this->supports 		= array('default_credit_card_form', 'products');

			// Add support for Woo Subscriptions, making sure PaySubs is not available for checkout on PayXML
			if($this->method !== 'payxml'){
				$this->supports[] = 'subscriptions';
			}
			
			// Available Card Types
			$this->available_card_types = array(
				'Visa' 		 => 'Visa',
				'MasterCard' => 'MasterCard'
			);
			if( $this->enable_diners == 'yes' ) $this->available_card_types['Diners'] 	= 'Diners Club';
			if( $this->enable_amex   == 'yes' ) $this->available_card_types['AmEx'] 	= 'American Express';

			// Testing Credentials
			$this->paygate_test_id 	= '10011013800';
			$this->test_ip          = '255.255.255.255';		

			// Method filtering
			switch( $this->method ):
				case 'payxml':
					$this->paygate_test_key	= 'test';
					$this->payment_url 		= 'https://www.paygate.co.za/payxml/process.trans';
					add_action( 'admin_notices', array( &$this, 'ssl_check') );
				break;
				case 'payweb':
					$this->paygate_test_key	= 'secret';
					$this->payment_url 		= 'https://www.paygate.co.za/paywebv2/process.trans';
					
					// PaySubs Support
					$this->paygate_paysubs_url = 'https://www.paygate.co.za/paysubs/process.trans';
					$this->paygate_paysubs_version = 21;

					// Add Hooks for Validation and Receipt
					add_action( 'valid-paygate-request', array( &$this, 'successful_request' ) );
					add_action( 'woocommerce_receipt_paygate', array( &$this, 'receipt_page' ) );

					// Handle IPN here when the gateway is loaded on the receipt page
					add_action( 'wp', array( &$this, 'check_paygate_payweb_response' ) );
				break;
			endswitch;

			// 3D Secure URL's
			$this->threedee_notification_url = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/gateway-paygate-payxml-notify.php';
			$this->threedee_response_url	 = add_query_arg('wc-api', 'WC_Gateway_Paygate', get_home_url(null, '/'));

			/* 2.0.0 WCAPI call to handle PayWeb POST response and 3D Secure Listener for PayXML payments */
			add_action( 'woocommerce_api_wc_gateway_paygate', array( &$this, 'paygate_response_handler' ) );

			// Currencies
			$this->available_currencies = array('ZAR');

			/* 1.6.6 */
			add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );

			/* 2.0.0 */
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
			
		}

		# -------------------------------------------------- #
		# Compatibility Functions -------------------------- #
		# -------------------------------------------------- #

		private function is_two_point_one()
		{

			global $woocommerce;
			$current_version = $woocommerce->version;
			
			if($current_version < '2.1.0')
				return false;

			return true;

		}

		# -------------------------------------------------- #
		# Admin Functions ---------------------------------- #
		# -------------------------------------------------- #

		// Settings API setup
		public function init_form_fields() {

			$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'woothemes' ),
								'label' => __( 'Enable PayGate', 'woothemes' ),
								'type' => 'checkbox',
								'description' => '',
								'default' => 'no'
							),
				'title' => array(
								'title' => __( 'Title', 'woothemes' ),
								'type' => 'text',
								'description' => __( 'This controls the title which the user sees during checkout.', 'woothemes' ),
								'default' => __( 'Credit Card (PayGate)', 'woothemes' )
							),
				'description' => array(
								'title' => __( 'Description', 'woothemes' ),
								'type' => 'textarea',
								'description' => __( 'This controls the description which the user sees during checkout.', 'woothemes' ),
								'default' => 'Pay with your credit card using PayGate.'
							),
				'show_description' => array(
								'title' => __( 'Show the Description', 'woothemes' ),
								'type' => 'select',
								'description' => __( 'This controls the output of the payment gateway\'s description on the checkout page.', 'woothemes' ),
								'default' => 'yes',
								'options' => array(
									'yes' => 'Yes',
									'no' => 'No'
								)
							),
				'method' => array(
								'title' => __( 'Payment Method', 'woothemes' ),
								'type' => 'select',
								'description' => __( 'This controls which payment method the gateway uses.', 'woothemes' ),
								'default' => 'payweb',
								'options' => array(
									'payweb' => 'PayWeb',
									'payxml' => 'PayXML'
								)
							),
				'testmode' => array(
								'title' => __( 'PayGate Test Mode', 'woothemes' ),
								'label' => __( 'Enable PayGate Test Mode', 'woothemes' ),
								'type' => 'checkbox',
								'description' => __( 'Place the payment gateway in development mode.', 'woothemes' ),
								'default' => 'no'
							),
				'paygate_id' => array(
								'title' => __( 'PayGate ID', 'woothemes' ),
								'type' => 'text',
								'description' => __( 'Get your credentials from PayGate.', 'woothemes' ),
								'default' => ''
							),
				'paygate_key' => array(
								'title' => __( 'PayGate Password (Secret Key)', 'woothemes' ),
								'type' => 'text',
								'description' => __( 'Get your credentials from PayGate.', 'woothemes' ),
								'default' => ''
							),
				'enable_diners' => array(
								'title' => __( 'Accept Diners Club', 'woothemes' ),
								'label' => __( 'Accept Diners Club card payments', 'woothemes' ),
								'type' => 'checkbox',
								'description' => __( '<strong>PayXML Only</strong>: Allow customers to pay with Diners Club Credit Cards. Contact PayGate to enable this facility.', 'woothemes' ),
								'default' => 'no'
							),
				'enable_amex' => array(
								'title' => __( 'Accept American Express', 'woothemes' ),
								'label' => __( 'Accept American Express card payments', 'woothemes' ),
								'type' => 'checkbox',
								'description' => __( '<strong>PayXML Only</strong>: Allow customers to pay with American Express Credit Cards. Contact PayGate to enable this facility.', 'woothemes' ),
								'default' => 'no'
							)
				);
		}

		// Generate the admin area forms
		public function admin_options() { 
			
			if($this->is_woo_subscriptions_active()){ ?>
				<div id="message" class="updated woocommerce-message wc-connect woocommerce-subscriptions-activated">
					<div class="squeezer">
						<h4>PayGate PaySubs</h4>
						<p>You're using WooCommerce Subscriptions. The PayGate payment extension for WooCommerce has built-in support for PayGate's PaySubs service and will automatically handle subscription payments for subscription products. <strong>Please ensure you have activated the PaySubs service with PayGate</strong>.</p>
						<p><strong>Please note:</strong> 
							<ol>
								<li>PaySubs only works with alongside PayWeb, and cannot be used together with PayXML. This is a limitation of PayGate.</li>
							</ol>
						</p>
					</div>
				</div>
			<?php } ?>
			<h3><?php echo $this->method_title; ?></h3>
			<p><?php _e( 'The <a href="http://www.paygate.co.za" target="_blank">PayGate</a> gateway works by either allowing your customers to handle credit card purchases on your website (PayXML) or by redirecting your customers to a secure portal (PayWeb) to handle the payment. You will need to have a merchant account with PayGate and an account with a South African financial institution for this gateway to work.', 'woothemes' ); ?></p>
			<table class="form-table">
				<?php $this->generate_settings_html(); ?>
			</table><!--/.form-table-->
		<?php }

		# -------------------------------------------------- #
		# PayWeb/PayXML Functions -------------------------- #
		# -------------------------------------------------- #

		public function is_available() {

			if( $this->enabled=="yes") :

				if( $this->method == 'payxml' ):
					if( get_option('woocommerce_force_ssl_checkout') == 'no' && $this->testmode == 'no' )
						return false;
				endif;

				if( !$this->testmode ){
					if( !$this->paygate_key || !$this->paygate_id )
						return false;
				}

				if( !in_array( get_option('woocommerce_currency' ), $this->available_currencies ) ) return false;

				return true;
			endif;

			return false;

		}

		// Payment Form on Checkout
		public function payment_fields() {

			switch( $this->method ):

				case 'payweb':
					if ($this->description !== '' && $this->show_description == 'yes') : ?><p class="description"><?php echo wptexturize( $this->description ); ?></p><?php endif;
				break;
				case 'payxml':

				// Generate CC form for versions < 2.1
				if(!$this->is_two_point_one()){

					if( $this->testmode=='yes' ) : ?><p><?php _e('<strong>TEST MODE/SANDBOX ENABLED</strong>', 'woothemes'); ?></p><?php endif; ?>
					<?php if ($this->description !== '' && $this->show_description == 'yes') : ?><p class="description"><?php echo wptexturize( $this->description ); ?></p><?php endif; ?>
					<p><?php _e('<strong>Please Note:</strong> If you are registered for SecureCode/Verified by Visa, you will be redirected to enter your pin number.', 'woothemes'); ?></p>
					<fieldset>
						<p class="form-row form-row-first">
							<label for="paygate_card_number"><?php echo __("Credit Card number", 'woocommerce') ?> <span class="required">*</span></label>
							<input type="text" class="input-text" name="paygate_card_number" />
						</p>
						<p class="form-row form-row-last">
							<label for="paygate_card_type"><?php echo __("Card type", 'woocommerce') ?> <span class="required">*</span></label>
							<select id="paygate_card_type" name="paygate_card_type" class="woocommerce-select">
								<?php foreach ($this->available_card_types as $card => $label) : ?>
									<option value="<?php echo $card ?>"><?php echo $label; ?></options>
								<?php endforeach; ?>
							</select>
						</p>
						<div class="clear"></div>
						<p class="form-row form-row-first">
							<label for="cc-expire-month"><?php echo __("Expiration date", 'woocommerce') ?> <span class="required">*</span></label>
							<select name="paygate_card_expiration_month" id="cc-expire-month" class="woocommerce-select woocommerce-cc-month">
								<option value=""><?php _e('Month', 'woocommerce') ?></option>
								<?php
									$months = array();
									for ($i = 1; $i <= 12; $i++) :
										$timestamp = mktime(0, 0, 0, $i, 1);
										$months[date('n', $timestamp)] = date('F', $timestamp);
									endfor;
									foreach ($months as $num => $name) printf('<option value="%u">%s</option>', $num, $name);
								?>
							</select>
							<select name="paygate_card_expiration_year" id="cc-expire-year" class="woocommerce-select woocommerce-cc-year">
								<option value=""><?php _e('Year', 'woocommerce') ?></option>
								<?php
									for ($i = date('y'); $i <= date('y') + 15; $i++) printf('<option value="%u">20%u</option>', $i, $i);
								?>
							</select>
						</p>
						<p class="form-row form-row-last">
							<label for="paygate_card_csc"><?php _e("Card security code", 'woocommerce') ?> <span class="required">*</span></label>
							<input type="text" class="input-text" id="paygate_card_csc" name="paygate_card_csc" maxlength="4" style="width:4em;" />
							<span class="help paygate_card_csc_description"></span>
						</p>
						<div class="clear"></div>
					</fieldset>
					<script type="text/javascript">

						jQuery("#paygate_card_type").change(function(){

							var card_type = jQuery("#paygate_card_type").val();
							var csc = jQuery("#paygate_card_csc").parent();

							if( card_type == "Visa" || card_type == "MasterCard" || card_type == "Diners" || card_type == "AmEx" ) {
								csc.fadeIn("fast");
							} else {
								csc.fadeOut("fast");
							}

							if( card_type == "Visa" || card_type == "MasterCard" || card_type == "Diners") {
								jQuery('.paygate_card_csc_description').text("<?php _e('3 digits usually found on the signature strip.', 'woocommerce'); ?>");
							} else if(  cardType == "AmEx" ) {
								jQuery('.paygate_card_csc_description').text("<?php _e('4 digits usually found on the front of the card.', 'woocommerce'); ?>");
							} else {
								jQuery('.paygate_card_csc_description').text('');
							}

						}).change();

					</script>
				<?php }else{

					// Use WC 2.1 built in CC form
					$this->credit_card_form();

				}
				break;

			endswitch;
		}

		// Process the payment
		public function process_payment($order_id) {
			global $woocommerce;

			$order = new WC_Order( $order_id );

			if( $this->method == 'payxml' ):

				if(!$this->is_two_point_one()){

					$card_type 			= isset($_POST['paygate_card_type']) ? woocommerce_clean($_POST['paygate_card_type']) : '';
					$card_number 		= isset($_POST['paygate_card_number']) ? woocommerce_clean($_POST['paygate_card_number']) : '';
					$card_csc 			= isset($_POST['paygate_card_csc']) ? woocommerce_clean($_POST['paygate_card_csc']) : '';
					$card_exp_month		= isset($_POST['paygate_card_expiration_month']) ? woocommerce_clean($_POST['paygate_card_expiration_month']) : '';
					$card_exp_year 		= isset($_POST['paygate_card_expiration_year']) ? woocommerce_clean($_POST['paygate_card_expiration_year']) : '';

					// Format card expiration data
					$card_exp_month = (int) $card_exp_month;
					if( $card_exp_month < 10) :
						$card_exp_month = '0'.$card_exp_month;
					endif;

					$card_exp_year = (int) $card_exp_year;
					$card_exp_year += 2000;
					$card_exp = $card_exp_month . $card_exp_year;

				}else{

					$card_number 		= isset($_POST['paygate-card-number']) ? woocommerce_clean($_POST['paygate-card-number']) : '';
					$card_csc 			= isset($_POST['paygate-card-cvc']) ? woocommerce_clean($_POST['paygate-card-cvc']) : '';
					$card_exp			= isset($_POST['paygate-card-expiry']) ? woocommerce_clean($_POST['paygate-card-expiry']) : '';

					// Format card expiration data
					$card_exp = explode('/', str_replace(' ', '', $card_exp));
					$card_exp_month =$card_exp[0];
					$card_exp_year = (int) $card_exp[1];
					$card_exp_year += 2000; // 83 years to go before this has to be changed.
					$card_exp = $card_exp_month . $card_exp_year;

				}

				// Format card number
				$card_number = str_replace(array(' ', '-'), '', $card_number);

				// Allow for Test Mode
				if($this->testmode == 'yes'):
					$this->paygate_id 	= $this->paygate_test_id;
					$this->paygate_key	= $this->paygate_test_key;
				endif;

				// Send request to paygate
				try {

					$paygate_xml = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE protocol SYSTEM "https://www.paygate.co.za/payxml/payxml_v4.dtd"><protocol ver="4.0" pgid="'.$this->paygate_id.'" pwd="'.$this->paygate_key.'"><authtx cref="'.$order_id.'" cname="'.$order->billing_first_name . ' ' . $order->billing_last_name.'" cc="'.$card_number.'" exp="'.$card_exp.'" budp="0" amt="'.$this->prepare_order_total($order->order_total).'" cur="'.get_option('woocommerce_currency').'" cvv="'.$card_csc.'" nurl="'.$this->threedee_notification_url.'" rurl="'.$this->threedee_response_url.'" email="'.$order->billing_email.'" ip="'.$this->get_user_ip().'" /></protocol>';
				
					$args = array(
						'headers' => array(
							'content-type'      => 'text/xml',
							'content-length'    => strlen($paygate_xml)
						),
						'timeout' 		=> 60,
						'sslverify' 	=> false,
						'user-agent' 	=> 'WooCommerce',
						'body'          => $paygate_xml
					);
					
					$paygate_response = wp_remote_post( $this->payment_url, $args );

					if( !is_wp_error( $paygate_response ) ) {
						$response_xml = simplexml_load_string($paygate_response['body']);
						$paygate_response = '';
					} else {
						$order->add_order_note( sprintf(__('PayGate PayXML payment failed. Payment was rejected due to an error: %s', 'woothemes'), 'Error connecting to PayGate.') );

						if(!$this->is_two_point_one()){
							return $woocommerce->add_error(__('Payment Error: Error connecting to PayGate.', 'woothemes'));
						}else{
							return wc_add_notice(__('Payment Error: Error connecting to PayGate.', 'woothemes'), 'error');
						}

					}
										
					foreach($response_xml->children() as $child){
						switch($child->getName()){
							case 'errorrx': // Returns Errors
								foreach($child->attributes() as $attr){
									$paygate_response['errorrx'][$attr->getName()] = (string)$attr[0][0];
								}
																
								$order->add_order_note( sprintf(__('PayGate PayXML payment failed. Payment was rejected due to an error: [%s] %s', 'woothemes'), $paygate_response['errorrx']['ecode'], $paygate_response['errorrx']['edesc']) );

								if(!$this->is_two_point_one()){
									return $woocommerce->add_error(__('Payment Error: ['.$paygate_response['errorrx']['ecode'].'] ' . $paygate_response['errorrx']['edesc'] . '.', 'woothemes'));
								}else{
									return wc_add_notice(__('Payment Error: ['.$paygate_response['errorrx']['ecode'].'] ' . $paygate_response['errorrx']['edesc'] . '.', 'woothemes'), 'error');
								}
								
							break;
							case 'authrx': // Standard Authorization / No 3DSecure
								foreach($child->attributes() as $attr){
									$paygate_response['authrx'][$attr->getName()] = (string)$attr[0][0];
								}
								
								switch($paygate_response['authrx']['stat']):
									case '0': // Failed
										
										switch($paygate_response['authrx']['res']):
											case '990022': $error_message = 'Bank Not Available. Please wait a few minutes and attempt your purchase again.'; break;
											case '990024': $error_message = 'Duplicate Transaction detected. Please refresh the page, and attempt your purchase again.'; break;
											case '990053': $error_message = 'There was an error processing your transaction, please wait a few minutes and attempt your purchase again.'; break;
										endswitch;
										
										$order->add_order_note( sprintf(__('PayGate PayXML payment failed. Payment was rejected due to an error: %s', 'woothemes'), $error_message) );

										if(!$this->is_two_point_one()){
											return $woocommerce->add_error(__('Payment Error: ' . $error_message . '.', 'woothemes'));
										}else{
											return wc_add_notice(__('Payment Error: ' . $error_message . '.', 'woothemes'), 'error');
										}
										
									break;
									case '1': // Payment Successful
										if($paygate_response['authrx']['res'] == '990017'): // Successful Transaction

											$order->add_order_note( sprintf(__('PayGate PayXML payment completed (Transaction ID: %s)', 'woothemes'), $paygate_response['authrx']['tid'] ) );
											$order->payment_complete();
											$woocommerce->cart->empty_cart();
											unset($_SESSION['order_awaiting_payment']);
											return array(
												'result' 	=> 'success',
												'redirect'	=> $this->get_return_url($order)
											);

										endif;
									break;
									case '2': // Payment Unsuccessful
										switch($paygate_response['authrx']['res']):
											case '900001': $error_message = 'Sorry, your transaction was declined: Please call for approval and attempt your purchase again.'; break;
											case '900002': $error_message = 'Sorry, your transaction was declined: Card Expired.'; break;
											case '900003': $error_message = 'Sorry, your transaction was declined: Insufficient Funds.'; break;
											case '900004': $error_message = 'Sorry, your transaction was declined: Invalid Card Number.'; break;
											case '900005': $error_message = 'Sorry, your transaction failed: Bank Interface Timeout.'; break;
											case '900006': $error_message = 'Sorry, your transaction failed: Invalid Card.'; break;
											case '900007': $error_message = 'Sorry, your transaction was declined.'; break;
											case '900009': $error_message = 'Sorry, your transaction failed: Card reported as lost.'; break;
											case '900010': $error_message = 'Sorry, your transaction failed: Invalid Card Length.'; break;
											case '900011': $error_message = 'Sorry, your transaction failed: Suspected Fraud.'; break;
											case '900012': $error_message = 'Sorry, your transaction failed: Card Reported As Stolen.'; break;
											case '900013': $error_message = 'Sorry, your transaction failed: Restricted Card.'; break;
											case '900014': $error_message = 'Sorry, your transaction failed: Excessive Card Usage.'; break;
											case '900015': $error_message = 'Sorry, your transaction failed: Card Blacklisted.'; break;
											case '900207': $error_message = 'Sorry, your transaction failed: 3D Secure Declined.'; break;
											case '990020': $error_message = 'Sorry, your transaction failed: Authorization Declined.'; break;
											case '991001': $error_message = 'Sorry, your transaction failed: Invalid expiry date.'; break;
											case '991002': $error_message = 'Sorry, your transaction failed: Invalid Amount.'; break;
										endswitch;
										$order->add_order_note( sprintf(__('PayGate PayXML payment failed. Payment was rejected due to an error: %s', 'woothemes'), $error_message) );
										if(!$this->is_two_point_one()){
											return $woocommerce->add_error( __($error_message . '<br /> Please attempt your purchase again.', 'woothemes') );
										}else{
											return wc_add_notice( __($error_message . '<br /> Please attempt your purchase again.', 'woothemes'), 'error' );
										}
										
									break;
								endswitch;
							break;
							case 'securerx': // 3D Secure Payment
								foreach($child->attributes() as $attr){
									$paygate_res['securerx'][$attr->getName()] = (string)$attr[0][0];
								}

								$secure_url = $paygate_res['securerx']['url'].'?PAYGATE_ID='.$this->paygate_id.'&TRANS_ID='.$paygate_res['securerx']['tid'].'&CHECKSUM='.$paygate_res['securerx']['chk'];

								return array(
									'result' 	=> 'success',
									'redirect'	=> $secure_url
								);
							break;
						}
					}

				} catch(Exception $e) {

					if(!$this->is_two_point_one()){
						return $woocommerce->add_error(__('Connection error:', 'woothemes') . ': "' . $e->getMessage() . '"');
					}else{
						return wc_add_notice(__('Connection error:', 'woothemes') . ': "' . $e->getMessage() . '"', 'error');
					}

				}

			else:

				return array(
					'result' 	=> 'success',
					'redirect'	=> $order->get_checkout_payment_url( true )
				);

			endif;
		}

		# -------------------------------------------------- #
		# PayXML Specific Functions ------------------------ #
		# -------------------------------------------------- #

		// Check that SSL is enabled
		public function ssl_check() {
			if(  $this->method == 'payxml' && get_option('woocommerce_force_ssl_checkout') == 'no' && $this->enabled == 'yes' ) :
				echo '<div class="error"><p>'.sprintf(__('PayGate PayXML is enabled, but the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate - PayGate PayXML will only work in test mode.', 'woothemes'), admin_url('admin.php?page=woocommerce')).'</p></div>';
			endif;
		}

		// Validate the input fields on checkout
		public function validate_fields() {
			global $woocommerce;

			if($this->method == 'payxml'):

				if(!$this->is_two_point_one()){

					$card_type 			= isset($_POST['paygate_card_type']) ? woocommerce_clean($_POST['paygate_card_type']) : '';
					$card_number 		= isset($_POST['paygate_card_number']) ? woocommerce_clean($_POST['paygate_card_number']) : '';
					$card_csc 			= isset($_POST['paygate_card_csc']) ? woocommerce_clean($_POST['paygate_card_csc']) : '';
					$card_exp_month		= isset($_POST['paygate_card_expiration_month']) ? woocommerce_clean($_POST['paygate_card_expiration_month']) : '';
					$card_exp_year 		= isset($_POST['paygate_card_expiration_year']) ? woocommerce_clean($_POST['paygate_card_expiration_year']) : '';

				}else{

					$card_number 		= isset($_POST['paygate-card-number']) ? woocommerce_clean($_POST['paygate-card-number']) : '';
					$card_csc 			= isset($_POST['paygate-card-cvc']) ? woocommerce_clean($_POST['paygate-card-cvc']) : '';
					$card_exp			= isset($_POST['paygate-card-expiry']) ? woocommerce_clean($_POST['paygate-card-expiry']) : '';
					$card_type 			= $this->get_credit_card_type( $card_number );

					// Format card expiration data
					$card_exp = explode('/', str_replace(' ', '', $card_exp));
					$card_exp_month =$card_exp[0];
					$card_exp_year = $card_exp[1];

				}
			
				// Remove any formatting on CC number.
				$card_number = str_replace(array(' ', '-'), '', $card_number);

				// Check card type is available
				$available_cards = $this->available_card_types;
				if( !in_array($card_type, $available_cards)) :
										
					if(!$this->is_two_point_one()){
						return $woocommerce->add_error(__('The selected credit card type is not available.', 'woothemes'));
					}else{
						return wc_add_notice(__('The selected credit card type is not available.', 'woothemes'), 'error');
					}

				endif;

				// Check card security code
				if( !ctype_digit($card_csc)) :
					
					if(!$this->is_two_point_one()){
						return $woocommerce->add_error(__('Card security code is invalid (only digits are allowed).', 'woothemes'));
					}else{
						return wc_add_notice(__('Card security code is invalid (only digits are allowed).', 'woothemes'), 'error');
					}

				endif;

				if( (strlen($card_csc) != 3 && in_array($card_type, array('Visa', 'MasterCard', 'Diners'))) || (strlen($card_csc) != 4 && $card_type == 'AmEx')) :
					
					if(!$this->is_two_point_one()){
						return $woocommerce->add_error(__('Card security code is invalid (wrong length).', 'woothemes'));
					}else{
						return wc_add_notice(__('Card security code is invalid (wrong length).', 'woothemes'), 'error');
					}

				endif;

				// Check if the selected card type matches the number
				if( !$this->paygate_validate_card_type( $card_number, $card_type ) ){
					
					if(!$this->is_two_point_one()){
						return $woocommerce->add_error(__('Selected card type doesn\'t match card number.', 'woothemes'));
					}else{
						return wc_add_notice(__('Selected card type doesn\'t match card number.', 'woothemes'), 'error');
					}

				}

				// Check card expiration data
				if(
					!ctype_digit($card_exp_month) ||
					!ctype_digit($card_exp_year) ||
					$card_exp_month > 12 ||
					$card_exp_month < 1 ||
					$card_exp_year < date('y') ||
					$card_exp_year > date('y') + 20
				) :
										
					if(!$this->is_two_point_one()){
						return $woocommerce->add_error(__('Card expiration date is invalid.', 'woothemes'));
					}else{
						return wc_add_notice(__('Card expiration date is invalid.', 'woothemes'), 'error');
					}

				endif;

				// Check card number

				if( empty($card_number) || !ctype_digit($card_number) ) :
					
					if(!$this->is_two_point_one()){
						return $woocommerce->add_error(__('Card number is invalid.', 'woothemes'));
					}else{
						return wc_add_notice(__('Card number is invalid.', 'woothemes'), 'error');
					}

				endif;

				return true;
			else:
				return true;
			endif;
		}

		/* Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org *
		 * This code has been released into the public domain, however please      *
		 * give credit to the original author where possible.                      */

		private function paygate_validate_cc_luhn( $c )
		{
			// Strip any non-digits (useful for credit card numbers with spaces and hyphens)
			$number=preg_replace('/\D/', '', $number);

			// Set the string length and parity
			$number_length = strlen($number);
			$parity = $number_length % 2;

			// Loop through each digit and do the maths
			$total=0;
			for ($i=0; $i<$number_length; $i++) {
				$digit=$number[$i];

				// Multiply alternate digits by two
				if ($i % 2 == $parity) {
					$digit*=2;
					// If the sum is two digits, add them together (in effect)
					if ($digit > 9) {
						$digit-=9;
					}
				}

				// Total up the digits
				$total+=$digit;
			}

			// If the total mod 10 equals 0, the number is valid
			return ($total % 10 == 0) ? true : false;
		}

		private function paygate_validate_card_type( $cnum, $selected_type )
		{
			$type = '';

			// Currently only validates against MasterCard, Visa, Diners and AmEx
			if (preg_match("/^5[1-5][0-9]{14}$/", $cnum))              $type = "MasterCard";
			if (preg_match("/^4[0-9]{12}([0-9]{3})?$/", $cnum))        $type = "Visa";
			if (preg_match("/^3[47][0-9]{13}$/", $cnum))               $type = "American Express";
			if (preg_match("/^3(0[0-5]|[68][0-9])[0-9]{11}$/", $cnum)) $type = "Diners Club";

			if( $selected_type !== $type){
				return false;
			}

			return true;
		}

		public function paygate_threedee_redirect( $url ){
			global $woocommerce;
			echo '<script type="text/javascript">
				jQuery(function(){
					jQuery("body").block({
						message: "<img src=\"'.$woocommerce->plugin_url().'/assets/images/ajax-loader.gif\" alt=\"Redirecting...\" />'.__('Your payment requires 3D Secure Verification, you will now be redirected to handle the verification.', 'woothemes').'",
						overlayCSS: {
							background: "#fff",
							opacity: 0.6
						},
						css: {
							padding: 20,
							textAlign: "center",
							color: "#555",
							border: "3px solid #aaa",
							backgroundColor:"#fff",
							cursor: "wait"
						}
					});
				});
			</script>';
		}

		public function paygate_response_handler() {
			global $woocommerce;
			
			@ob_clean();
			header('HTTP/1.1 200 OK');
			
			if( isset($_POST) && !empty($_POST) ):

				$posted = $_POST;
				$paygate_key = ($this->testmode == 'yes') ? 'test' : $this->paygate_key;
				$paygate_key = ($this->method == 'payweb' && $this->testmode == 'yes') ? 'secret' : $paygate_key;
				
				// check valid response, and no tampering
				$valid_checksum = ($this->method == 'payxml') ? md5($posted['PAYGATE_ID'].'|'.$posted['REFERENCE'].'|'.$posted['CARD_TYPE'].'|'.$posted['TRANSACTION_STATUS'].'|'.$posted['RESULT_CODE'].'|'.$posted['RESULT_DESC'].'|'.$posted['AUTH_CODE'].'|'.$posted['TRANSACTION_ID'].'|'.$posted['RISK_INDICATOR'].'|'.$paygate_key) : md5($posted['PAYGATE_ID'].'|'.$posted['REFERENCE'].'|'.$posted['TRANSACTION_STATUS'].'|'.$posted['RESULT_CODE'].'|'.$posted['AUTH_CODE'].'|'.$posted['AMOUNT'].'|'.$posted['RESULT_DESC'].'|'.$posted['TRANSACTION_ID'].'|'.$posted['RISK_INDICATOR'].'|'.$paygate_key);
				
				// Check for PaySubs Response
				if(isset($posted['SUBSCRIPTION_ID'])){
					$valid_checksum = md5($posted['PAYGATE_ID'].'|'.$posted['REFERENCE'].'|'.$posted['TRANSACTION_STATUS'].'|'.$posted['RESULT_CODE'].'|'.$posted['AUTH_CODE'].'|'.$posted['AMOUNT'].'|'.$posted['RESULT_DESC'].'|'.$posted['TRANSACTION_ID'].'|'.$posted['SUBSCRIPTION_ID'].'|'.$posted['RISK_INDICATOR'].'|'.$paygate_key);
				}
				
				if($posted['CHECKSUM'] == $valid_checksum):
					$order = new WC_Order( (int) $posted['REFERENCE'] );

					if ($order->id <> $posted['REFERENCE']):

						$order->add_order_note( sprintf(__('Payment Received, but order ID did not match reference: code %s - %s.', 'woothemes'), $posted['REFERENCE'], $response->response_reason_text ) );
						if ($order->status == 'pending' || $order->status == 'failed'):
							$order->update_status( 'on-hold' );
						endif;

						$woocommerce->cart->empty_cart();
						wp_safe_redirect( $this->get_return_url($order) );
						exit;

					endif;

					if ($order->status !== 'completed'):
						switch($posted['TRANSACTION_STATUS']):
							case '0': // Transaction Cancelled on the PayGate Website by clicking Cancel
								wp_safe_redirect( $woocommerce->cart->get_checkout_url() );
								exit;
							break;
							case '1': // Transaction Approved
								if($posted['RESULT_CODE'] == '990017'):

									// Payment completed
									if($this->method == 'payxml'){
										$order->add_order_note( __('PayGate PayXML (3D Secure) Payment Completed<br /><strong>PayGate Transaction ID</strong>: '.$posted['TRANSACTION_ID'], 'woothemes') );
									}else{
										$order->add_order_note( __('PayGate PayWeb Payment Completed<br /><strong>PayGate Transaction ID</strong>: '.$posted['TRANSACTION_ID'], 'woothemes') );
									}
									$order->payment_complete();
									$woocommerce->cart->empty_cart();
									wp_safe_redirect( $this->get_return_url($order) );
									exit;
								endif;
							break;
							case '2': // Transaction Declined
								switch($posted['RESULT_CODE']):
									case '900001': $paygate_response = 'Call for Approval'; break;
									case '900002': $paygate_response = 'Card Expired'; break;
									case '900003': $paygate_response = 'Insufficient Funds'; break;
									case '900004': $paygate_response = 'Invalid Card Number'; break;
									case '900005': $paygate_response = 'Bank Interface Timeout'; break;
									case '900006': $paygate_response = 'Invalid Card'; break;
									case '900007': $paygate_response = 'Declined'; break;
									case '900009': $paygate_response = 'Lost Card'; break;
									case '900010': $paygate_response = 'Invalid Card Length'; break;
									case '900011': $paygate_response = 'Suspected Fraud'; break;
									case '900012': $paygate_response = 'Card Reported As Stolen'; break;
									case '900013': $paygate_response = 'Restricted Card'; break;
									case '900014': $paygate_response = 'Excessive Card Usage'; break;
									case '900015': $paygate_response = 'Card Blacklisted'; break;
									case '900207': $paygate_response = '3D Secure Declined'; break;
									case '990020': $paygate_response = 'Auth Declined'; break;
									case '991001': $paygate_response = 'Invalid expiry date'; break;
									case '991002': $paygate_response = 'Invalid Amount'; break;
								endswitch;

								if(!$this->is_two_point_one()){
									$woocommerce->add_error(__('Transaction Declined: '. $paygate_response .'.', 'woothemes'));
								}else{
									wc_add_notice(__('Transaction Declined: '. $paygate_response .'.', 'woothemes'), 'error');
								}

								wp_safe_redirect( $woocommerce->cart->get_checkout_url() );

								exit;
							break;
						endswitch;
					endif;

				endif;
			else:
				wp_safe_redirect(home_url('/'));
				exit;
			endif;

		}

		/* Get Users IP Address */
		private function get_user_ip() {
			if( $this->testmode == 'yes'):
				return $this->test_ip;
			endif;
			return (isset($_SERVER['HTTP_X_FORWARD_FOR']) && !empty($_SERVER['HTTP_X_FORWARD_FOR'])) ? $_SERVER['HTTP_X_FORWARD_FOR'] : $_SERVER['REMOTE_ADDR'];
		}

		# -------------------------------------------------- #
		# PayWeb Specific Functions ------------------------ #
		# -------------------------------------------------- #

		// Receipt page
		public function receipt_page( $order_id ) {

			echo '<p>'.__('Thank you for your order.', 'woothemes').'</p>';
			echo $this->generate_paygate_payweb_form( $order_id );

		}

		// Generate POST values for submission
		public function generate_paygate_payweb_form( $order_id ) {
			global $woocommerce;
			
			// Update PayGate ID and Secret Key for Test Mode
			if ( $this->testmode == 'yes' ):
				$this->paygate_id = $this->paygate_test_id;
				$this->paygate_key = $this->paygate_test_key;
			endif;
			
			$order = new WC_Order( $order_id );
			$order_total = $this->prepare_order_total($order->order_total);

			// Remove Seconds from Order Date to comply with PayGate Requirements
			$paygate_order_date = strtotime($order->order_date);
			$paygate_order_date = date('Y-m-d H:i', $paygate_order_date);

			// Create required MD5 checksum
			$paygate_checksum = md5($this->paygate_id.'|'.$order_id.'|'.$order_total.'|'.get_option('woocommerce_currency').'|'.$this->threedee_response_url.'|'.$paygate_order_date.'|'.$this->paygate_key);

			$paygate_payweb_args = array_merge(
				array(
					'PAYGATE_ID'		=> $this->paygate_id,
					'REFERENCE'			=> $order_id,
					'AMOUNT'			=> $order_total,
					'CURRENCY'			=> get_option('woocommerce_currency'),
					'RETURN_URL'		=> $this->threedee_response_url,
					'TRANSACTION_DATE'	=> $paygate_order_date,
					'CHECKSUM'			=> $paygate_checksum
				)
			);
			
			// Handle WooCommerce Subscriptions Orders
			$paysubs_args_array = array();
			if($this->is_woo_subscriptions_active()){
				if ( WC_Subscriptions_Order::order_contains_subscription( $order_id ) ) {
				
					// Update the processing URL
					$this->payment_url = $this->paygate_paysubs_url;
				
					$subs_details = array(
						'initial_payment' => WC_Subscriptions_Order::get_total_initial_payment( $order ),
						'billing_period'  => WC_Subscriptions_Order::get_subscription_period( $order ),
						'interval'		  => WC_Subscriptions_Order::get_subscription_interval( $order ),
						'trial_period'    => WC_Subscriptions_Order::get_subscription_trial_period( $order ),
						'price_per_period'=> WC_Subscriptions_Order::get_price_per_period( $order ),
						'subs_length'	  => WC_Subscriptions_Order::get_subscription_length( $order )
					);
				
					// Calculate Start and End Dates
					$start_date = date('Y-m-d');
					$end_date = date('Y-m-d', strtotime($paygate_order_date . '+ 1 year'));
					if($subs_details['subs_length'] != '')
						$end_date = date('Y-m-d', strtotime($paygate_order_date . '+ ' . $subs_details['subs_length'] . ' ' . $subs_details['billing_period']));
						
					// Calculate the PayGate Frequency Code based on Subscription Details
					$frequency_code = 201; // Default to First of Month / Monthly
				
					// Monthly
					if($subs_details['billing_period'] == 'month'){
						$day_of_month = date('d', strtotime($paygate_order_date));
					
						// Setup Interval
						$interval_code = 2;
						if($subs_details['interval'] > 1 && $subs_details['interval'] < 3) // Every 2nd Month
							$interval_code = 3;
						if($subs_details['interval'] > 2) // Every 3rd Month
							$interval_code = 4;	
					
						// Set frequency code for last day of the month if date is greater than 28th
						if($day_of_month > 28){
							$frequency_code = $interval_code . 29;
						}else{
							$frequency_code = $interval_code . $day_of_month;
						}
					}
				
					// Weekly
					if($subs_details['billing_period'] == 'week'){
						$day_of_week = strtolower(date('D', strtotime($paygate_order_date))); // Get the day of the week
						
						// Get integer representation of the week day.
						$week_id = null;
						switch($day_of_week):
							case 'sun': $week_id = 1; break;
							case 'mon': $week_id = 2; break;
							case 'tue': $week_id = 3; break;
							case 'wed': $week_id = 4; break;
							case 'thu': $week_id = 5; break;
							case 'fri': $week_id = 6; break;
							case 'sat': $week_id = 7; break;
						endswitch;
					
						// Set the frequency code.
						$frequency_code = 1 . $subs_details['interval'] . $week_id;
					
					}
				
					$paysubs_args_array['VERSION'] = $this->paygate_paysubs_version;
					$paysubs_args_array['SUBS_START_DATE'] = $start_date;
					$paysubs_args_array['SUBS_END_DATE'] = $end_date;
					$paysubs_args_array['SUBS_FREQUENCY'] = $frequency_code;
					$paysubs_args_array['PROCESS_NOW'] = 'YES';
					$paysubs_args_array['PROCESS_NOW_AMOUNT'] = $order_total;
				
					// Generate an updated checksum for PaySubs
					$paygate_checksum = md5($paysubs_args_array['VERSION'].'|'.$this->paygate_id.'|'.$order_id.'|'.$order_total.'|'.get_option('woocommerce_currency').'|'.$this->threedee_response_url.'|'.$paygate_order_date.'|'.$paysubs_args_array['SUBS_START_DATE'].'|'.$paysubs_args_array['SUBS_END_DATE'].'|'.$paysubs_args_array['SUBS_FREQUENCY'].'|'.$paysubs_args_array['PROCESS_NOW'].'|'.$paysubs_args_array['PROCESS_NOW_AMOUNT'].'|'.$this->paygate_key);
					$paygate_payweb_args['CHECKSUM'] = $paygate_checksum;
				
					// Merge the PaySubs Array into the Main Args Array
					$paygate_payweb_args = array_merge($paysubs_args_array, $paygate_payweb_args);
								
				}
			}
					
			$paygate_payweb_args_array = array();

			foreach ($paygate_payweb_args as $key => $value) {
				$paygate_payweb_args_array[] = '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
			}

			return '<form action="'.$this->payment_url.'" method="POST" id="paygate_payweb_payment_form">
				' . implode('', $paygate_payweb_args_array) . '
				</form>
				<a class="button cancel" href="'.$order->get_cancel_order_url().'">'.__('Cancel order &amp; restore cart', 'woothemes').'</a>
				<script type="text/javascript">
					jQuery(function(){
						jQuery("body").block({
							message: "<img src=\"'.$woocommerce->plugin_url().'/assets/images/ajax-loader.gif\" alt=\"Redirecting...\" />'.__('Thank you for your order. We are now redirecting you to PayGate to make payment.', 'woothemes').'",
							overlayCSS: {
								background: "#fff",
								opacity: 0.6
							},
							css: {
								padding: 20,
								textAlign: "center",
								color: "#555",
								border: "3px solid #aaa",
								backgroundColor:"#fff",
								cursor: "wait"
							}
						});
						jQuery("#paygate_payweb_payment_form").submit();
					});
				</script>';
		}

		// Post callback function
		public function check_paygate_payweb_response() {

			@ob_clean();

			if(isset($_POST) && !empty($_POST)):
				if(isset($_POST['TRANSACTION_STATUS'])):
					do_action("valid-paygate-request", $_POST);
				endif;
			endif;

		}

		// If check_paygate_payweb_response() is successful, process the response
		public function successful_request( $posted ) {
			global $woocommerce;

			// Allow test mode
			if(  $this->testmode == 'yes' ):
				$this->paygate_id = $this->paygate_test_id;
				$this->paygate_key = $this->paygate_test_key;
			endif;
			
			// check valid response, and no tampering
			$valid_checksum = md5($posted['PAYGATE_ID'].'|'.$posted['REFERENCE'].'|'.$posted['TRANSACTION_STATUS'].'|'.$posted['RESULT_CODE'].'|'.$posted['AUTH_CODE'].'|'.$posted['AMOUNT'].'|'.$posted['RESULT_DESC'].'|'.$posted['TRANSACTION_ID'].'|'.$posted['RISK_INDICATOR'].'|'.$this->paygate_key);

			if($posted['CHECKSUM'] == $valid_checksum):

				$order = new WC_Order( (int) $posted['REFERENCE'] );

				if( $order->id <> $posted['REFERENCE']) exit;

				if( $order->status !== 'completed'):
					switch($posted['TRANSACTION_STATUS']):
						case '0': // Transaction Cancelled on the PayGate Website by clicking Cancel
							switch($posted['RESULT_CODE']):
								case '990022': $error_message = 'Bank Not Available. Please wait a few minutes and attempt your purchase again.'; break;
								case '990024': $error_message = 'Duplicate Transaction detected. Please refresh the page, and attempt your purchase again.'; break;
								case '990053': $error_message = 'There was an error processing your transaction, please wait a few minutes and attempt your purchase again.'; break;
								case '990028': $error_message = 'Transaction was cancelled by the customer.'; break;
							endswitch;
							$order->update_status('failed', __($error_message, 'woothemes') );
							return;
						break;
						case '1': // Transaction Approved
							if($posted['RESULT_CODE'] == '990017'):
								// Payment completed
								$order->add_order_note( __('PayGate PayWeb Payment Completed<br /><strong>PayGate Transaction ID</strong>: '.$posted['TRANSACTION_ID'], 'woothemes') );
								$woocommerce->cart->empty_cart();
								$order->payment_complete();
							endif;
						break;
						case '2': // Transaction Declined
							switch($posted['RESULT_CODE']):
								case '900001': $paygate_response = 'Call for Approval'; break;
								case '900002': $paygate_response = 'Card Expired'; break;
								case '900003': $paygate_response = 'Insufficient Funds'; break;
								case '900004': $paygate_response = 'Invalid Card Number'; break;
								case '900005': $paygate_response = 'Bank Interface Timeout'; break;
								case '900006': $paygate_response = 'Invalid Card'; break;
								case '900007': $paygate_response = 'Declined'; break;
								case '900009': $paygate_response = 'Lost Card'; break;
								case '900010': $paygate_response = 'Invalid Card Length'; break;
								case '900011': $paygate_response = 'Suspected Fraud'; break;
								case '900012': $paygate_response = 'Card Reported As Stolen'; break;
								case '900013': $paygate_response = 'Restricted Card'; break;
								case '900014': $paygate_response = 'Excessive Card Usage'; break;
								case '900015': $paygate_response = 'Card Blacklisted'; break;
								case '900207': $paygate_response = '3D Secure Declined'; break;
								case '990020': $paygate_response = 'Auth Declined'; break;
								case '991001': $paygate_response = 'Invalid expiry date'; break;
								case '991002': $paygate_response = 'Invalid Amount'; break;
							endswitch;
							$order->update_status('failed', sprintf(__('Payment Failed: Payment Response is "%s" via PayGate.', 'woothemes'), $paygate_response ) );
						break;
					endswitch;
				endif;

			endif;
		}

		# -------------------------------------------------- #
		# Private Functions -------------------------------- #
		# -------------------------------------------------- #
		
		private function is_woo_subscriptions_active()
		{
			if(class_exists('WC_Subscriptions_Order'))
				return true;
			
			return false;
		}

		private function get_credit_card_type($card_number)
		{

			$card_number = str_replace(array(' ', '-'), '', $card_number);

			if (preg_match("/^5[1-5][0-9]{14}$/", $card_number))              return "MasterCard";
			if (preg_match("/^4[0-9]{12}([0-9]{3})?$/", $card_number))        return "Visa";
			if (preg_match("/^3[47][0-9]{13}$/", $card_number))               return "American Express";
			if (preg_match("/^3(0[0-5]|[68][0-9])[0-9]{11}$/", $card_number)) return "Diners Club";

			return null;

		 }
		 
		 private function prepare_order_total( $amount )
		 {
			 
			 // Check for decimals
			 $has_decimals = true;
			 if(get_option('woocommerce_price_num_decimals') == 0)
				 $has_decimals = false;
			 
			 if(!$has_decimals)
				 $amount = $amount . '.00'; // PayGate requires amounts to be in cent value, this adds the missing zeros for shops without decimals on prices.
			 
 			 $amount = str_replace('.','', $amount); // Strip decimal point (if included)
			 return $amount;
			 
		 }

	}
	
	# -------------------------------------------------- #
	# WooSubs Filters ---------------------------------- #
	# -------------------------------------------------- #
	
	// Reduces the number of interval periods to 3 (PayGate Restriction)
	add_filter( 'woocommerce_subscription_period_interval_strings', 'paygate_reduce_period_intervals');
	function paygate_reduce_period_intervals( $intervals ) 
	{
			$count = 0;
			$new_intervals = array();
			foreach($intervals as $interval){
				if($count <= 2)
					$new_intervals[] = $interval;
				$count++;
			}
			return $new_intervals;
	}

	/* Add the gateway to WooCommerce */
	function add_paygate_gateway( $methods ) {
		$methods[] = 'WC_Gateway_Paygate'; return $methods;
	}
	add_filter('woocommerce_payment_gateways', 'add_paygate_gateway' );

}
?>