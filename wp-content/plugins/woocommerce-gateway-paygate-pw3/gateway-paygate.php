<?php
/*
  Plugin Name: PayGate PayWeb3 plugin for WooCommerce
  Plugin URI: https://www.paygate.co.za
  Description: Accept payments for WooCommerce using PayGate's PayWeb3 service
  Version: 1.0.0
  Author: PayGate
  Author URI: https://www.paygate.co.za
	Requires at least: 3.5
	Tested up to: 4.1.1
*/

add_action( 'plugins_loaded', 'woocommerce_paygate_init', 0 );

/**
 * Initialize the gateway.
 *
 * @since 1.0.0
 */
function woocommerce_paygate_init() {

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;

	require_once( plugin_basename( 'classes/paygate.class.php' ) );

	add_filter('woocommerce_payment_gateways', 'woocommerce_add_paygate_gateway' );

} // End woocommerce_paygate_init()

/**
 * Add the gateway to WooCommerce
 *
 * @since 1.0.0
 */
function woocommerce_add_paygate_gateway( $methods ) {
	$methods[] = 'WC_Gateway_PayGate';
	return $methods;
} // End woocommerce_add_paygate_gateway()