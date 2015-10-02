=== WooCommerce PayGate Gateway ===
By Byron Rode - http://www.wearetangram.com

== About the PayGate Gateway ==
The PayGate [http://www.paygate.co.za] payment gateway extends WooCommerce by allowing the customer to make payments using either the PayGate PayWeb [http://www.paygate.co.za/payweb.php] or PayGate PayXML [http://www.paygate.co.za/payxml.php] merchant facilities. PayGate is a trusted and specialist credit card payment services provider that has been operating since 1996 and works with all major financial institutions in South Africa.

Please Note:

- A PayGate Merchant Account is required to use this payment gateway.
- This payment gateway will only accept South African Rand (ZAR) as the currency when processing payments. Other currencies are not accepted, and the payment gateway will not be available if you are using another currency as default. 
- If you use the PayXML facility, Diners Club and American Express are accepted, but must be activated by PayGate and enabled in the gateway settings. By default only Visa and MasterCard are accepted.
- International Cards are accepted.
- If you choose Test Mode, you will be able to process payments, but all valid credit cards will returned as declined and your card will not be processed. The PayGate documentation have a list of card numbers to use for testing.
- For (older) PayGate accounts that do not require 3D Secure verification, make sure to disable the 3D Secure verification in the gateway settings.


== PayWeb ==
The PayWeb facility will take customers off-site to the secure PayGate portal to handle payment and (any) 3D secure verification and then redirect the user back to the website once completed to finalize the transaction.

== PayXML ==
The PayXML is a more advance facility will allow customers to make payment without redirecting customers off-site (unless they are required to input any 3D Secure information). Requires a SSL ready server and certificate to be installed on the website, and will not work in live mode, unless WooCommerce's Force-SSL is enabled. cURL support is also required.

== Installation	 ==
1. Download and unzip the latest release zip file.
2. If you use the WordPress plugin uploader to install this plugin skip to step 4.
3. Upload the entire plugin directory to your /wp-content/plugins/ directory.
4. Activate the plugin through the 'Plugins' menu in WordPress Administration.
5. Go WooCommerce Settings --> Payment Gateways and configure your PayGate PayXML settings.