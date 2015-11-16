<?php
	/**
	 * PayGate Payment Gateway
	 *
	 * Provides a PayGate PayWeb3 Payment Gateway.
	 *
	 * @class       woocommerce_paygate
	 * @package     WooCommerce
	 * @category    Payment Gateways
	 * @author      PayGate
	 *
	 */
	class WC_Gateway_PayGate extends WC_Payment_Gateway {

		public $version = '1.0.2';

		public function __construct(){
			global $woocommerce;

			$this->id                 = 'paygate';
			$this->method_title       = __('PayGate via PayWeb3', 'paygate');
			$this->method_description = __('PayGate via Payweb3 works by sending the customer to PayGate to complete their payment.', 'paygate');
			$this->icon               = $this->plugin_url() . '/assets/images/logo.png';
			$this->has_fields         = false;
			$this->order_button_text  = __('Proceed to PayGate', 'paygate');
			$this->initiate_url       = 'https://secure.paygate.co.za/payweb3/initiate.trans';
			$this->process_url        = 'https://secure.paygate.co.za/payweb3/process.trans';
			$this->query_url          = 'https://secure.paygate.co.za/payweb3/query.trans';

			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();

			// Define user set variables
			$this->merchant_id    = $this->settings['paygate_id'];
			$this->encryption_key = $this->settings['encryption_key'];
			$this->title          = $this->settings['title'];
			$this->description    = $this->get_option('description');

			$this->msg['message'] = "";
			$this->msg['class']   = "";

			// Setup the test data, if in test mode.
			if($this->settings['testmode'] == 'yes'){
				$this->add_testmode_admin_settings_notice();
			}

			$this->response_url = str_replace('https:', 'http:', add_query_arg('wc-api', 'WC_Gateway_PayGate', home_url('/')));

			add_action('woocommerce_api_wc_gateway_paygate', array($this, 'check_paygate_notify_response'));
			add_action('woocommerce_thankyou_paygate', array($this, 'check_paygate_response'));

			if(version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')){
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
			} else {
				add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
			}

			add_action('woocommerce_receipt_paygate', array($this, 'receipt_page'));
		}

		/**
		 * Initialise Gateway Settings Form Fields
		 *
		 * @since 1.0.0
		 */
		function init_form_fields(){

			$this->form_fields = array(
				'enabled'        => array(
					'title'       => __('Enable/Disable', 'paygate'),
					'label'       => __('Enable PayGate Payment Gateway', 'paygate'),
					'type'        => 'checkbox',
					'description' => __('This controls whether or not this gateway is enabled within WooCommerce.', 'paygate'),
					'desc_tip'    => true,
					'default'     => 'no'
				),
				'title'          => array(
					'title'       => __('Title', 'paygate'),
					'type'        => 'text',
					'description' => __('This controls the title which the user sees during checkout.', 'paygate'),
					'desc_tip'    => false,
					'default'     => __('PayGate Payment Gateway', 'paygate')
				),
				'description'    => array(
					'title'       => __('Description', 'paygate'),
					'type'        => 'textarea',
					'description' => __('This controls the description which the user sees during checkout.', 'paygate'),
					'default'     => 'Pay via PayGate'
				),
				'testmode'       => array(
					'title'       => __('Test mode', 'paygate'),
					'type'        => 'checkbox',
					'description' => __('Place the payment gateway in development mode.', 'paygate'),
					'desc_tip'    => true,
					'default'     => 'yes'
				),
				'paygate_id'     => array(
					'title'       => __('PayGate ID', 'paygate'),
					'type'        => 'text',
					'description' => __('This is the PayGate ID, received from PayGate.', 'paygate'),
					'desc_tip'    => true,
					'default'     => ''
				),
				'encryption_key' => array(
					'title'       => __('Encryption Key', 'paygate'),
					'type'        => 'text',
					'description' => __('This is the Encryption Key set in the PayGate Back Office.', 'paygate'),
					'desc_tip'    => true,
					'default'     => ''
				)
			);

		} // End init_form_fields()

		/**
		 * add_testmode_admin_settings_notice()
		 *
		 * Add a notice to the merchant_key and merchant_id fields when in test mode.
		 *
		 * @since 1.0.0
		 */
		function add_testmode_admin_settings_notice(){
			$this->form_fields['paygate_id']['description'] .= ' <br><br><strong>' . __('PayGate ID currently in use.', 'paygate') . ' ( 10011013800 )</strong>';
			$this->form_fields['encryption_key']['description'] .= ' <br><br><strong>' . __('PayGate Encryption Key currently in use.', 'paygate') . ' ( secret )</strong>';
		} // End add_testmode_admin_settings_notice()

		/**
		 * Get the plugin URL
		 *
		 * @since 1.0.0
		 */
		function plugin_url(){
			if(isset($this->plugin_url))
				return $this->plugin_url;

			if(is_ssl()){
				return $this->plugin_url = str_replace('http://', 'https://', WP_PLUGIN_URL) . "/" . plugin_basename(dirname(dirname(__FILE__)));
			} else {
				return $this->plugin_url = WP_PLUGIN_URL . "/" . plugin_basename(dirname(dirname(__FILE__)));
			}
		} // End plugin_url()

		/**
		 * Admin Panel Options
		 * - Options for bits like 'title'
		 *
		 * @since 1.0.0
		 */
		public function admin_options(){
			?>
			<h3><?php _e('PayGate Payment Gateway', 'paygate'); ?></h3>
			<p><?php printf(__('PayGate works by sending the user to %sPayGate%s to enter their payment information.', 'paygate'), '<a href="https://www.paygate.co.za/">', '</a>'); ?></p>

			<table class="form-table"><?php
					// Generate the HTML For the settings form.
					$this->generate_settings_html();
				?></table><!--/.form-table-->
		<?php
		} // End admin_options()

		/**
		 * There are no payment fields for PayGate, but we want to show the description if set.
		 *
		 * @since 1.0.0
		 */
		function payment_fields(){
			if(isset($this->settings['description']) && ('' != $this->settings['description'])){
				echo wpautop(wptexturize($this->settings['description']));
			}
		} // End payment_fields()

		/**
		 * Generate the PayGate button link.
		 *
		 * @since 1.0.0
		 */
		public function generate_paygate_form($order_id){
			global $woocommerce;

			$order = new WC_Order($order_id);

			parse_str($this->initiate_response['body'], $parsed_response);

			wc_enqueue_js('
    			$.blockUI({
    					message: "<img src=\"' . $woocommerce->plugin_url() . '/assets/images/ajax-loader.gif\" alt=\"Redirecting...\" /><br>' . esc_js(__('Thank you for your order. We are now redirecting you to PayGate to make payment.', 'paygate')) . '",
    					baseZ: 99999,
    					overlayCSS:
    					{
    						background: "#fff",
    						opacity: 0.6
    					},
    					css: {
    						padding:        "20px",
    						zindex:         "9999999",
    						textAlign:      "center",
    						color:          "#555",
    						border:         "3px solid #aaa",
    						backgroundColor:"#fff",
    						cursor:         "wait",
    						lineHeight:		"24px",
    					}
    				});
    			jQuery("#submit_paygate_payment_form").click();
    		');

			unset($parsed_response['CHECKSUM']);
			$checksum = md5(implode('', $parsed_response) . $this->encryption_key);
			return '<p>' . __('Thank you for your order, please click the button below to pay via PayGate.', 'paygate') . '</p>' .
			'<form action="' . $this->process_url . '" method="post" id="paygate_payment_form">
                  	<input name="PAY_REQUEST_ID" type="hidden" value="' . $parsed_response['PAY_REQUEST_ID'] . '" />
                  	<input name="CHECKSUM" type="hidden" value="' . $checksum . '" />
          					<!-- Button Fallback -->
          					<div class="payment_buttons">
          						<input type="submit" class="button alt" id="submit_paygate_payment_form" value="' . __('Pay via PayGate', 'paygate') . '" /> <a class="button cancel" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'paygate') . '</a>
          					</div>
          					<script type="text/javascript">
          						jQuery(".payment_buttons").hide();
          					</script>
            			</form>';
		} 
		
		// End generate_paygate_form()

		/**
		 * Process the payment and return the result.
		 *
		 * @since 1.0.0
		 */
		function process_payment($order_id){
			$order = new WC_Order($order_id);

			return array(
				'result'   => 'success',
				'redirect' => $order->get_checkout_payment_url(true)
			);

		}

		function initiate_transaction($order_id){
			global $woocommerce;

			$order = new WC_Order($order_id);

			unset($this->data_to_send);
			
			if($this->settings['testmode'] == 'yes'){
				$this->merchant_id    = '10011013800';
				$this->encryption_key = 'secret';
			}
			
			// Construct variables for post
			$this->data_to_send             = array(
				'PAYGATE_ID'       => $this->merchant_id,
				'REFERENCE'        => 'Order ' . $order->get_order_number(),
				'AMOUNT'           => number_format($order->order_total, 2, '', ''),
				'CURRENCY'         => get_woocommerce_currency(),
				'RETURN_URL'       => esc_url($this->get_return_url($order)),
				'TRANSACTION_DATE' => date('Y-m-d H:m:s'),
				'LOCALE'           => 'en-za',
				'COUNTRY'          => 'ZAF',
				'EMAIL'            => $order->billing_email,
				'NOTIFY_URL'       => $this->response_url
			);
			$this->data_to_send['CHECKSUM'] = md5(implode('', $this->data_to_send) . $this->encryption_key);

			$this->initiate_response = wp_remote_post($this->initiate_url, array(
					'method'      => 'POST',
					'body'        => $this->data_to_send,
					'timeout'     => 70,
					'sslverify'   => false,
					'user-agent'  => 'WooCommerce',
					'httpversion' => '1.1'
				)
			);
	
			if(is_wp_error($this->initiate_response)){
				return $this->initiate_response;
			}

			parse_str($this->initiate_response['body'], $parsed_response);

			if(empty($this->initiate_response['body']) || array_key_exists('ERROR', $parsed_response) || !array_key_exists('PAY_REQUEST_ID', $parsed_response)){
				$this->msg['class']   = 'woocommerce-error';
				$this->msg['message'] = "Thank you for shopping with us. However, we were unable to initiate your payment. Please try again.";
				$order->update_status('failed');
				$order->add_order_note('Response from initiating payment:' . print_r($this->data_to_send, true) . ' ' . $this->initiate_response['body']);
				return new WP_Error('paygate-error', __($this->showMessage('<br><a class="button wc-forward" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'paygate') . '</a>'), 'paygate'));
			}
		}

		/**
		 * Receipt page.
		 *
		 * Display text and a button to direct the customer to PayGate.
		 *
		 * @since 1.0.0
		 */
		function receipt_page($order){
			$return = $this->initiate_transaction($order);
			if(is_wp_error($return)){
				echo $return->get_error_message();
			} else {
				echo $this->generate_paygate_form($order);
			}
		} // End receipt_page()

		/**
		 * Show Message.
		 *
		 * Display message depending on order results.
		 *
		 * @since 1.0.0
		 */
		function showMessage($content){
			return '<div class="' . $this->msg['class'] . '">' . $this->msg['message'] . '</div>' . $content;
		}

		/**
		 * Check for valid PayGate Redirect
		 *
		 * @since 1.0.0
		 */
		function check_paygate_response(){
			global $woocommerce;
			
			if(isset($_GET['key']) && isset($_POST['PAY_REQUEST_ID'])){
				$key      = $_GET['key'];
				$order_id = wc_get_order_id_by_order_key($key);
				if($order_id != ''){
					$order = wc_get_order($order_id);

					$pay_request_id = $_POST['PAY_REQUEST_ID'];
					$status         = $_POST['TRANSACTION_STATUS'];
					$checksum       = $_POST['CHECKSUM'];
					
					if($this->settings['testmode'] == 'yes'){
						$this->merchant_id    = '10011013800';
						$this->encryption_key = 'secret';
					}
					
					$checksum_source = $this->merchant_id . $pay_request_id . $status . 'Order ' . $order->get_order_number() . $this->encryption_key;
					$test_checksum   = md5($checksum_source);
					if($order->status !== 'processing' || $order->status !== 'completed'){
						
						if($checksum == $test_checksum){
							
							$fields             = array(
								'PAYGATE_ID'     => $this->merchant_id,
								'PAY_REQUEST_ID' => $_POST['PAY_REQUEST_ID'],
								'REFERENCE'      => 'Order ' . $order->get_order_number()
							);
							$fields['CHECKSUM'] = md5(implode('', $fields) . $this->encryption_key);
							$response           = wp_remote_post($this->query_url, array(
									'method'      => 'POST',
									'body'        => $fields,
									'timeout'     => 70,
									'sslverify'   => false,
									'user-agent'  => 'WooCommerce/' . WC_VERSION,
									'httpversion' => '1.1'
								)
							);

							if(is_wp_error($response)){
								return false;
							}
							
							parse_str($response['body'], $parsed_response);
							
							if($parsed_response['TRANSACTION_STATUS'] == 1){
								$order->payment_complete();
								$order->add_order_note('Response via Redirect: Transaction successful<br/>PayGate Trans Id: ' . $parsed_response['TRANSACTION_ID'] . '<br/>');
								$woocommerce->cart->empty_cart();
								return true;
							} else if($parsed_response['TRANSACTION_STATUS'] == 4){
								$order->update_status('failed', 'Response via Redirect: User cancelled transaction<br/>PayGate Trans Id: ' . $parsed_response['TRANSACTION_ID'] . '<br/>');
							} else {
								$order->update_status('failed', 'Response via Redirect, RESULT_DESC: ' . $parsed_response['RESULT_DESC'] . '<br/>PayGate Trans Id: ' . $parsed_response['TRANSACTION_ID'] . '<br/>');
							}
						} else {
							$order->update_status('failed', 'Response via Redirect, Security Error: Checksum mismatch. Illegal access detected' . '<br/>');
						}
					}
				}
			}
			return false;
		}

		/**
		 * Check for valid PayGate Notify
		 *
		 * @since 1.0.0
		 */
		function check_paygate_notify_response(){
			global $woocommerce;

			if(isset($_POST)){

				// Tell PayGate notify we have received
				echo 'OK';
				
				$errors       = false;
				$paygate_data = array();
				$notify_data = array();
				//// Get notify data
				if(!$errors){
					$paygate_data = $this->getPostData();
					if($paygate_data === false){
						$errors = true;
					}
				}
				
				//// Verify security signature
				$checkSumParams = '';
				if($this->settings['testmode'] == 'yes'){
					$this->encryption_key = 'secret';
				}
				
				if(!$errors){
			
					foreach($paygate_data as $key => $val){
						$notify_data[$key] = stripslashes($val);
			
						if($key == 'PAYGATE_ID'){
							$checkSumParams .= $val;
						}
						if($key != 'CHECKSUM' && $key != 'PAYGATE_ID'){
							$checkSumParams .= $val;
						}
			
						if(sizeof($notify_data) == 0){
							$errors = true;
						}
					}
					
					$checkSumParams .= $this->encryption_key;
				}
				
				// Verify security signature
				
				if(!$errors){
					$checkSumParams = md5($checkSumParams);
					if($checkSumParams != $paygate_data['CHECKSUM']){
						$errors = true;
						$error_desc = 'Security Error: Checksum mismatch. Illegal access detected';
					}
				}
				
				$order_id = str_replace('Order', '', $paygate_data['REFERENCE']);
	
				if($order_id != ''){
					$order = wc_get_order(trim($order_id));
					if(!$errors){
						if($order->status !== 'processing' || $order->status !== 'completed'){
							if($paygate_data['TRANSACTION_STATUS'] == 1){
								if($order->status == 'processing'){
	
								} else {
									$order->payment_complete();
									$order->add_order_note('Response via Notify: Transaction successful<br/>PayGate Trans Id: ' . $paygate_data['TRANSACTION_ID'] . '<br/>');
								}
							} else if($paygate_data['TRANSACTION_STATUS'] == 4){
								$order->update_status('failed', 'Response via Notify, User cancelled transaction<br/>PayGate Trans Id: ' . $paygate_data['TRANSACTION_ID'] . '<br/>');
							} else {
								$order->update_status('failed', 'Response via Notify, RESULT_DESC: ' . $paygate_data['RESULT_DESC'] . '<br/>PayGate Trans Id: ' . $paygate_data['TRANSACTION_ID'] . '<br/>');
							}
							echo 'OK';
						}
					} else {
						$order->update_status('failed', 'Response via Notify, ' . $error_desc . '<br/>');
					}
				}
			}
	}
	
	function getPostData(){
		// Posted variables from ITN
		$nData = $_POST;
	
		// Strip any slashes in data
		foreach($nData as $key => $val)
			$nData[$key] = stripslashes($val);
	
		// Return "false" if no data was received
		if(sizeof($nData) == 0)
			return (false);
		else
			return ($nData);
	}
} // End Class