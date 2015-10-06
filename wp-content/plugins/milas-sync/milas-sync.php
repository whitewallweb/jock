<?php
/**
 * @package Milas_Sync
 * @version 1.0
 */
/*
Plugin Name: Milas Sync
Plugin URI: 
Description: Sync Woocommerce purchases to the MILAS API
Version: 1.0
Author URI: http://whitewallweb.net/
*/


class milasSync {
    static function get_complete_orders()  {
        global $wpdb;
        $milas_track = $wpdb->prefix . 'milas_track';
        $order_items = $wpdb->prefix . 'woocommerce_order_items';
        $item_meta = $wpdb->prefix . 'woocommerce_order_itemmeta';
        $posts = $wpdb->prefix . 'posts';
        $postmeta = $wpdb->prefix . 'postmeta';

        // Find complete orders by order_id
        // As well as orders that have not been sent to MILAS
        $sql = "SELECT $posts.ID FROM $posts 
        WHERE $posts.post_type = 'shop_order' 
        AND EXISTS (SELECT 1 FROM $postmeta WHERE meta_key = '_completed_date' AND $postmeta.post_id = $posts.ID)  
        AND NOT EXISTS (SELECT 1 FROM $milas_track WHERE $milas_track.order_id = $posts.ID AND milas_confirm = 1)";

        $results = $wpdb->get_results($sql);

        // Now loop through orders and process

        foreach ( $results as $result ) 
        {    
            $sql = "SELECT $postmeta.meta_key, $postmeta.meta_value FROM $postmeta WHERE $postmeta.post_id = $result->ID";

            $records = $wpdb->get_results($sql);
            $meta = array();
            foreach ($records as $record) {
                $meta += array($record->meta_key => $record->meta_value);
            }
            
            $orderNumber = $result->ID;
            $orderDate = $meta['_completed_date'];
            $orderDate = str_replace('-','.',$orderDate);
            $customerName = $meta['_shipping_first_name']. " ".$meta['_shipping_last_name'];
            $shippingAddress1 = $meta['_shipping_address_1'];
            $shippingAddress2 = $meta['_shipping_address_2'];
            $shippingCity = $meta['_shipping_city'];
            $shippingPostalCode = $meta['_shipping_postcode'];
            $billingEmail = $meta['_billing_email'];
            $billingPhone = $meta['_billing_phone'];
            
            $xml = '<?xml version="1.0" encoding="UTF-8"?><MilasEOrder><Document><AccountCode>325190</AccountCode><DocumentNumber>'.$orderNumber.'</DocumentNumber><Date>'.$orderDate.'</Date><DesiredDeliveryDate>'.$orderDate.'</DesiredDeliveryDate><AlternativeDeliveryDate></AlternativeDeliveryDate><Currency>ZAR</Currency><Notes></Notes><DispatchCenter>ISADW</DispatchCenter><ShipToName>'.$customerName.'</ShipToName><ShipToAddressLine1>'.$shippingAddress1.'</ShipToAddressLine1><ShipToAddressLine2>'.$shippingAddress2.'</ShipToAddressLine2><ShipToAddressLine3></ShipToAddressLine3><ShipToCity>'.$shippingCity.'</ShipToCity><ShipToPostalCode>'.$shippingPostalCode.'</ShipToPostalCode><ShipToCountry>RSA</ShipToCountry><ShipToLatitude></ShipToLatitude><ShipToLongitude></ShipToLongitude><ShipToPhoneNumber>'.$billingPhone.'</ShipToPhoneNumber><ShipToCellPhoneNumber></ShipToCellPhoneNumber><ShipToEmailAddress>'.$billingEmail.'</ShipToEmailAddress>';
            
            // Now fetch order items
            $sql = "SELECT order_item_id, order_item_name FROM $order_items WHERE order_item_type = 'line_item' AND order_id = $result->ID";
            $orderItems = $wpdb->get_results($sql);
            
            // Loop and collect details for each line item.
            foreach($orderItems as $orderItem) {

                $sql = "SELECT meta_key, meta_value FROM $item_meta WHERE order_item_id = $orderItem->order_item_id";
                $orderItemDetails = $wpdb->get_results($sql);
                
                $wpdb->query("INSERT IGNORE INTO $milas_track SET order_id = $result->ID");
                
                $meta = array();
                foreach($orderItemDetails as $detail) {
                    $meta += array($detail->meta_key => $detail->meta_value);
                }
                
                $packagingSize = $meta['weight'];
                $packagingSize = str_ireplace('kg','K',$packagingSize);
                
                if(intval($meta['_qty']) > 0) {
                    $unitPrice = intval($meta['_line_total']) / intval($meta['_qty']);
                }
                else {
                    $unitPrice = 0;
                }
                
                $product_id = $meta['_product_id'];
                
                $sku =  $wpdb->get_results("SELECT meta_value FROM wp_postmeta WHERE meta_key = '_sku' AND post_id = ".$product_id." LIMIT 1");
                
                $xml .= "<Item><ItemCode>".$sku[0]->meta_value."</ItemCode><Description>".$orderItem->order_item_name."</Description><Quantity>".$meta["_qty"]."</Quantity><Packaging>BAG</Packaging><PackagingSize>".$packagingSize."</PackagingSize><UnitPrice>".$unitPrice."</UnitPrice><TotalPriceExcl>".$meta['_line_subtotal']."</TotalPriceExcl><Vat>14</Vat><VatAmount>".$meta['_order_tax']."</VatAmount><TotalPriceIncl>".$meta['_line_total']."</TotalPriceIncl></Item>";
            }
            $xml .= "</Document></MilasEOrder>";
            $xml = urlencode($xml);
            
            $fp = fsockopen("nrafmil01", 80, $errno, $errstr, 30);
            if (!$fp) {
              error_log($fp);
            } else {
                $out = "GET /MilasWebService/MilasData.asmx/MilasEOrderRequest?XmlBuffer=" . $xml . " \r\n/ HTTP/1.1\r\n";
                $out .= "Host: localhost \r\n";
                fwrite($fp, $out);
                $status = (fread($fp, 556));
                if(stripos($status, "success") !== false)  {
                  //Now set processed flag
                  //$wpdb->insert( $table_name, array( 'order_id' => 'value1'), array('%d'));
                  $wpdb->query("UPDATE $milas_track SET milas_confirm = 1 WHERE order_id = $result->ID");
                }
                error_log($xml);
                fclose($fp);
            }
            
        }
    }
}

add_action('wp', array('milasSync', 'get_complete_orders'));
add_action('woocommerce_review_order_after_payment', array('milasSync', 'get_complete_orders'));
//add_action('woocommerce_review_order_after_submit', array('milasSync', 'get_complete_orders'));

// Initialise the plugin for first time use

global $mil_db_version;
$mil_db_version = '1.0';

function milas_install() {
	global $wpdb;
	global $mil_db_version;

	$table_name = $wpdb->prefix . 'milas_track';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		 ID int(11) NOT NULL AUTO_INCREMENT,
          order_id int(11) NOT NULL,
          milas_confirm tinyint(4) NOT NULL DEFAULT '0',
          order_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (ID),
          UNIQUE KEY order_id (order_id),
          KEY milas_confirm (milas_confirm)
        ) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'mil_db_version', $mil_db_version );
}

register_activation_hook( __FILE__, 'milas_install' );

// For future upgrades

function milas_update_db_check() {
    global $mil_db_version;
    if ( get_site_option( 'mil_db_version' ) != $mil_db_version ) {
        // Upgrade here
    }
}
add_action( 'plugins_loaded', 'milas_update_db_check' );

?>