<?php


include_once('Jock_LifeCycle.php');

class Jock_Plugin extends Jock_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'delivery_restrictions' => array(__('Gauteng Delivery Only?', 'my-awesome-plugin'), 'false', 'true'),
            
            'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Jock';
    }

    protected function getMainPluginFileName() {
        return 'jock.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
        
        
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // 
         
        // 
        //JD-87- check mobile number and
        add_action('woocommerce_checkout_process', array(&$this,'validate_checkout_field_process'));
        
        //JD-80  terms and conditions
        add_action( 'woocommerce_after_order_notes', array(&$this,'custom_jock_checkout_fields' ));
        
        
        //JD-82  Continue shopping
        add_action( 'woocommerce_after_cart_totals', array(&$this,'continue_shopping' ));
        
        //terms and conditions
        //LEARN HOW TO ADD CONTENT PAGES
        
        //JD-91 Delivery T&C's
        add_action( 'woocommerce_after_cart', array(&$this,'delivery_notice' ));
        
        //JD-86 - Gauteng only for launch
        add_filter('woocommerce_countries_shipping_country_states',array(&$this,'limit_delivery_states' ));
        add_filter('woocommerce_countries_allowed_country_states',array(&$this,'limit_delivery_states' ));
        
        
        //JD-95 Custome checkout form
        add_filter('woocommerce_checkout_fields',array(&$this,'custom_checkout_form' ));
        
        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41

    }
    
    
    //ACTIONS
    function validate_checkout_field_process() 
    {
        // Check if set, if its not set add an error.
        if ( ! $_POST['billing_phone']){
            wc_add_notice( __( 'Please enter phone number' ), 'error' );
        }
        $stripped = preg_replace( '/\D/', '', $_POST['billing_phone'] );
        $_POST['billing_phone'] = $stripped;
        if( strlen( $_POST['billing_phone'] ) != 10 ) { // Number string must equal this
             wc_add_notice( __( 'Please enter a 10 digit phone number.' ), 'error' );
        }
         if ( ! $_POST['terms_and_conditions']){
            wc_add_notice( __( 'Please accept the terms and conditions' ), 'error' );
        }
        //default values
        
        $_POST['billing_state'] = 'Gauteng';
        $_POST['billing_city'] = 'Gauteng';
        
    }
    
    function custom_jock_checkout_fields($checkout)
    {
        echo '<div id="billing_terms_and conditions">';

        woocommerce_form_field( 'terms_and_conditions', array(
            'type'          => 'checkbox',
            'class'         => array('my-field-class form-row-wide'),
            'label'         => __('I accept the <a target ="_blank" href="/general-conditions-of-online-supply">Terms and Conditions</a>'),
            'placeholder'   => __(''),
            ), $checkout->get_value( 'my_field_name' ));

        echo '</div>';
        
        echo "<script>$('#billing_suburb').change(function(){alert('hello')});</script>";
        
    }
    
    function continue_shopping()
    {
        echo '<div id="continue-shopping">';
        echo "<a class='checkout-button button alt wc-forward' href='/shop'>&nbsp;Continue Shopping</a>";
        echo '</div>';
    }
    
    function delivery_notice()
    {
        echo '<br/><div id="delivery-notice" class="alert-warning padded">';
        echo "<p class='text-center'><br/>Currently only delivering in Gauteng Area.<br/><br/></p>";
        echo '</div>';
        echo '<br/><div id="delivery-notice" class="alert-warning padded">';
        echo "<p class='text-center'><br/>Delivery takes place on weekdays only. No deliveries are done on weekends or public holidays.<br/><br/></p>";
        echo '</div>';
    }
    
    
    //FILTERS
    function limit_delivery_states($states)
    {
        
//        if(array_key_exists('ZA', $states))
//        {
            return array('ZA' => array(
        	'GP'  => __( 'Gauteng', 'woocommerce' )
                ));
//        }
//        else
//        {
//            return $states;
//        }
//        
    }
    
    function custom_checkout_form($fields)
    {
        
 
////          $fields['account']['account_password'] = array(
////                 'type'              => 'password',
////                 'label'             => __( 'Account password', 'woocommerce' ),
////                 'required'          => true,
////                 'placeholder'       => _x( 'Password', 'placeholder', 'woocommerce' )
////             );
//       //var_dump(  $fields['shipping']);
//          $fields['shipping'] = array_intersect_key($fields['shipping'],array_flip(array(
//              'shipping_country','shipping_state'
//          ))
//                  );
             $fields['shipping'] = array_merge(array_flip(array(
              'shipping_country',
              'shipping_state',
              'shipping_postcode',
              'shipping_city',
              'shipping_address_1',
              'shipping_address_2',
              'shipping_first_name',
              'shipping_last_name',
              'shipping_company',
              
          )),$fields['shipping'] ); 
        
             //new fields
             
             //get options from xml file
             
             
             $postal_codes = json_decode(json_encode(simplexml_load_file(dirname(__FILE__).'/languages/postal_codes/postal_codes.xml')),true);
             //var_dump($postal_codes);
             
             
             foreach($postal_codes['RECORD'] as $row)
             {
                
                 if(strstr(strtolower($row['group']), 'pretoria') || strstr(strtolower($row['group']), '(gp)')|| strstr(strtolower($row['group']), 'gauteng')|| strstr(strtolower($row['suburb']), 'gauteng'))
                 {
                     //$gauteng_postals[] =  $row;
                     $city_options[$row['group']] = ucwords(strtolower($row['group']));
                     $gauteng_postals_options[$row['post_code']] = ucwords(strtolower($row['suburb'])." (".$row['post_code'].")");
                 }
                 else
                {
                     continue;
                }
             }
             sort($gauteng_postals_options);
            
            $fields['billing']['billing_postcode'] = array(
                'type'=>'select',
                'label'=>'Suburb (Postal Code)',
                'required'=>1,
                'options'=>$gauteng_postals_options,
                'class'=>array('form-row-wide'));
            
             $fields['billing']['billing_city'] = array(
                'type'=>'select',
                'label'=>'City',
                'required'=>1,
                'options'=>$city_options,
                'class'=>array('form-row-wide'));
             
             
            $fields['billing'] = array_merge(array_flip(array(
              'billing_country',
              'billing_state',
                'billing_city',
              //'billing_suburb',    
              'billing_postcode',
              
              'billing_address_1',
              'billing_address_2',
              'billing_first_name',
              'billing_last_name',
              'billing_company',
              'billing_phone',  
              'billing_email',    
          )),$fields['billing'] ); 
           
         
            
          //change css
          $fields['billing']['billing_first_name']['class'][0]='form-row-wide';
          
          $fields['billing']['billing_state']['class'][0]='form-row-wide';
          $fields['billing']['billing_state']['type']='hidden';
          
          //$fields['billing']['billing_city']['type']='hidden';
          
          $fields['billing']['billing_postcode']['class'][0]='form-row-wide';
          //$fields['billing']['billing_postcode']['type']='hidden';
          
          $fields['billing']['billing_last_name']['class'][0]='form-row-wide';
          $fields['billing']['billing_phone']['class'][0]='form-row-wide';
          $fields['billing']['billing_email']['class'][0]='form-row-wide';
          
          
          
          return $fields;
   }

}
