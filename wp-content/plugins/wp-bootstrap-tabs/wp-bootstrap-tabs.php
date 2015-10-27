<?php
/*
Plugin Name: WP Bootstrap Tabs
Plugin URI: http://virtusdesigns.com/wp-bootstrap-tabs
Description: WP Bootstrap Tabs will help you to easily create tabs for your pages using the Twitter Bootstrap Tabs structure. The plug-in assumes you are already using Twitter Bootstrap in your theme so it does not load the Bootstrap CSS and JS for the tabs. If your theme is not using Bootstrap, you can load the necessary CSS and JS on the settings page.
Version: 1.0.4
Author: Virtus Designs
Author URI: http://virtusdesigns.com
WordPress version supported: 3.0 and above
*/

/*  Copyright 2010-2013  virtusdesigns.com  (email : brett@virtusdesigns.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'BOOTSTRAPTABS_PRO_ACTIVE' ) ):
if ( ! defined( 'BOOTSTRAPTABS_PLUGIN_BASENAME' ) )
	define( 'BOOTSTRAPTABS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
function bootstraptabs_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}
//on activation, your WP Bootstrap Tabs options will be populated. Here a single option is used which is actually an array of multiple options
function activate_bootstraptabs() {

}

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 12);


register_activation_hook( __FILE__, 'activate_bootstraptabs' );
global $bootstraptabs;
$bootstraptabs = get_option('bootstraptabs_options');
define("BOOTSTRAPTABS_VER","1.0.2",false);
define('BOOTSTRAPTABS_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );
include_once (dirname (__FILE__) . '/tinymce/tinymce.php');

function bootstraptabs_wp_init() {
			$options = get_option('bootstraptabs_options');
			if(!is_admin()){
				if($options['chk_default_options_css']){
				 	wp_enqueue_style("bootstraptabs_bootstrap", plugins_url('css/bootstrap-tab.css', __FILE__ ));
				}
				if($options['chk_default_options_js'])
					wp_enqueue_script('bootstraptabs_bootstrap', plugins_url('js/bootstrap-tab.js', __FILE__ ),array('jquery'));
			}	
			global $bootstraptabs_count,$bootstraptabs_tab_count,$bootstraptabs_content,$bootstraptabs_prev_post;
			$bootstraptabs_count=0;
			$bootstraptabs_tab_count=0;
			$bootstraptabs_prev_post='';
			$bootstraptabs_content=array();		
}
add_action( 'wp', 'bootstraptabs_wp_init' );

function bootstraptabs_edit_custom_box(){
	global $post;
	echo '<input type="hidden" name="enablebootstraptabs_noncename" id="enablebootstraptabs_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />'; 	?>
<?php
			$enablebootstraptabs = get_post_meta($post->ID,'enablebootstraptabs',true);
			if($enablebootstraptabs=="1"){
				$checked = ' checked="checked" ';
			}else{
				$checked = '';
			}
	?>

	<?php
}

function bootstraptabs_tab_shortcode($atts,$content) {
		extract(shortcode_atts(array(
			'name' => 'Tab Name',
			'link' => '',
			'active' => '',
		), $atts));
	
		global $bootstraptabs_content,$bootstraptabs_tab_count,$bootstraptabs_count;
		$bootstraptabs_content[$bootstraptabs_tab_count]['name'] = $name;
		$bootstraptabs_content[$bootstraptabs_tab_count]['link'] = $link;
		$bootstraptabs_content[$bootstraptabs_tab_count]['active'] = $active;	
		$bootstraptabs_content[$bootstraptabs_tab_count]['content'] = do_shortcode($content);
	    $bootstraptabs_tab_count = $bootstraptabs_tab_count+1;
	}
	add_shortcode('bootstrap_tab', 'bootstraptabs_tab_shortcode');
	
function bootstraptabs_end_shortcode($atts) {
	 global $bootstraptabs_content,$bootstraptabs_tab_count,$bootstraptabs_count;
	 
			if($bootstraptabs_tab_count!=0 and isset($bootstraptabs_tab_count)) {
			$tab_content = '<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">';	
				for($i=0;$i<$bootstraptabs_tab_count;$i++) {
	
					$tab_content = $tab_content.'<li class="tabs '.$bootstraptabs_content[$i]['active'].'"><a data-toggle="tab" href="#'.$bootstraptabs_content[$i]['link'].'">'.$bootstraptabs_content[$i]['name'].'</a></li>';
				}
				$tab_content = $tab_content.'</ul><div id="my-tab-content" class="tab-content">';
				
				$tab_html='';
				for($i=0;$i<$bootstraptabs_tab_count;$i++) {
					$link_html = $bootstraptabs_content[$i]['link'];
						$tab_html.='<div id="'.$bootstraptabs_content[$i]['link'].'" class="tab-pane '.$bootstraptabs_content[$i]['active'].'"><p>'.$bootstraptabs_content[$i]['content'].'</p></div>';
				}
				$tab_content = $tab_content.$tab_html;
			}
			$tab_content = $tab_content.'</div>';
			
			return $tab_content;
	}
	add_shortcode('end_bootstrap_tab', 'bootstraptabs_end_shortcode');

//Code to add settings page link to the main plugins page on admin
function bootstraptabs_admin_url( $query = array() ) {
	global $plugin_page;

	if ( ! isset( $query['page'] ) )
		$query['page'] = $plugin_page;

	$path = 'admin.php';

	if ( $query = build_query( $query ) )
		$path .= '?' . $query;

	$url = admin_url( $path );

	return esc_url_raw( $url );
}

add_filter( 'plugin_action_links', 'bootstraptabs_plugin_action_links', 10, 2 );

function bootstraptabs_plugin_action_links( $links, $file ) {
	if ( $file != BOOTSTRAPTABS_PLUGIN_BASENAME )
		return $links;

	$url = bootstraptabs_admin_url( array( 'page' => 'wp-bootstrap-tabs.php' ) );

	$settings_link = '<a href="' . esc_attr( $url ) . '">'
		. esc_html( __( 'Settings') ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}

// function for adding settings page to wp-admin
function bootstraptabs_settings() {
    // Add a new submenu under Tools:
    add_options_page('WP Bootstrap Tabs', 'WP Bootstrap Tabs', 9, basename(__FILE__), 'bootstraptabs_settings_page');
}

function bootstraptabs_admin_head() {?>
<?php }
add_action('admin_head', 'bootstraptabs_admin_head');

//Function to add custom style on settings page - version 1.4
function bootstraptabs_custom_css() {
	global $bootstraptabs;
	$css=$bootstraptabs['css'];
	if($css and !empty($css)){
		if( ( is_admin() and isset($_GET['page']) and 'wp-bootstraptabs-tabs.php' == $_GET['page']) or !is_admin() ){	?>
			<script type="text/javascript">jQuery(document).ready(function() { jQuery("head").append("<style type=\"text/css\"><?php echo $css;?></style>"); }) </script>
<?php 	}
	}
}
add_action('wp_footer', 'bootstraptabs_custom_css');
add_action('admin_footer', 'bootstraptabs_custom_css');

function bootstraptabs_plugin_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

function bootstraptabs_admin_scripts() {
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( isset($_GET['page']) && 'wp-bootstrap-tabs.php' == $_GET['page'] ) {
	wp_enqueue_style( 'bootstraptabs_admin_css', bootstraptabs_plugin_url( 'css/admin.css' ),
		false, BOOTSTRAPTABSPRO_VER, 'all');
	}
  }
}

add_action( 'admin_init', 'bootstraptabs_admin_scripts' );

function bootstraptabs_settings_page() {
?>
<div class="wrap">

<div style="width:65%;margin-top: 15px;">
	<div style="float:right;"><strong style="color:#ccc;font-size:9px;">powered by</strong> <a style="margin-left:5px;" href="http://virtusdesigns.com/" target="_blank" rel="nofollow"><img src="<?php echo bootstraptabs_plugin_url('images/virtus_logo_wp.png');?>" width="200"/></a> </div>
	<h2 style="font-size:26px;">WP Bootstrap Tabs</h2>
</div>

<form  method="post" action="options.php" id="bootstraptabs_form">
<div id="poststuff" class="metabox-holder has-right-sidebar"> 

<div  style="float:left;width:65%;" id="bootstraptabs_form">

<?php settings_fields('bootstraptabs-group');  ?>
<?php $options = get_option('bootstraptabs_options');  ?>








<div class="postbox">
<h3 class="hndle"><?php _e('Plug-in Info','bootstraptabs'); ?></h2>
<table class="form-table" style="border: none;">
			
				<tr><td colspan="2"><div style="margin-top:10px;">This plug-in assumes you are already using Twitter Bootstrap and does not load the necessary CSS and JS for the tabs.  If you are not using Bootstrap, you can still use the tabs by checking the boxes below. These files only load the CSS and JS for the tabs and not everything included in Twitter Bootstrap.</div></td></tr>

				<tr valign="top">
					<th scope="row">Twitter Bootstrap CSS</th>
					<td>
						<label><input name="bootstraptabs_options[chk_default_options_css]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_css'])) { checked('1', $options['chk_default_options_css']); } ?> /> Load Twitter Bootstrap css file</label><br /><span style="color:#666666;margin-left:2px;">Check this if you do not already include Bootstrap css in your them</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Twitter Bootstrap JS</th>
					<td>
						<label><input name="bootstraptabs_options[chk_default_options_js]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_js'])) { checked('1', $options['chk_default_options_js']); } ?> /> Load Twitter Bootstrap javascript file</label><br /><span style="color:#666666;margin-left:2px;">Check this if you do not already include Bootstrap javascript in your theme</span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
<p>This plug-in uses [bootstrap_tab][/bootstrap_tab] to set your information for the tabs and content.  You can set three attributes.</p>
<ol>
<li>name= allows you to set the title of your tab</li>
<li>link= lets you set the tab list item anchor and the corresponding content div id</li>
<li>active= lets you set which tab you want to display on page load. Only use on one tab</li>
</ol>

<p>Bootstrap tabs also uses a second shortcode, [end_bootstrap_tab], to display the actual tabs so you must include this once after the others.</p>

<p>To add the tabset, you can copy as paste the text below or use the button on the text editor.</p>

<p style="text-align: center; width: 90%; max-width: 471px; margin: 0 auto 10px;"><img class="editor-button" src="<?php echo bootstraptabs_plugin_url('images/editor-button.png');?>" /></p>

<table style="background-color: white; padding: 0px 20px;; margin: 0 auto; width: 90%; max-width: 471px;">
<tbody>
<tr>
<td>
<p>[bootstrap_tab name="Tab 1" link="tab1-slug" active="active"]</p>
<p>Content for Tab 1</p>
<p>[/bootstrap_tab]</p>
<p>[bootstrap_tab name="Tab 2" link="tab2-slug" ]</p>
<p>Content for Tab 2</p>
<p>[/bootstrap_tab]</p>
<p>[bootstrap_tab name="Tab 3" link="tab3-slug"]</p>
<p>Content for Tab 3</p>
<p>[/bootstrap_tab]</p>
<p>[end_bootstrap_tab]</p>
</td>
</tr>
</tbody>
</table>

<p>The structure of the shortcodes allows you to easily add it to your template files and combine it with Advanced Custom Fields repeater fields for a much better method of managing the tab content.</p>
<p>To see an example of how I have done this, please visit <a href=" http://www.virtusdesigns.com/2013/02/using-repeater-fields-with-tabs/" target="_blank">virtusdesigns.com</a></p>

<div style="padding:10px">
 
</div>
</div>

<div style="clear:both;"></div>

</div>

<div style="float:left;width:255px;padding-left:20px;"> 

			<div class="postbox"> 
			  <h3 class="hndle"><span><?php _e('About this Plugin:','bootstraptabs'); ?></span></h3> 
			  <div class="inside">
			  
			  
			  <div style="float:left;width:50%;margin-right:10px">
                <ul>
                <li><a href="http://virtusdesigns.com/wp-bootstrap-tabs/" title="<?php _e('WP Bootstrap Tabs Homepage','bootstraptabs'); ?>" target="_blank"><?php _e('Plugin Homepage','bootstraptabs'); ?></a></li>
                <li><a href="http://www.virtusdesigns.com/about/" title="<?php _e('WP Bootstrap Tabs Author Page','bootstraptabs'); ?>" target="_blank" ><?php _e('About the Author','bootstraptabs'); ?></a></li>
				<li><a href="http://virtusdesigns.com" title="<?php _e('Visit Virtus Designs','bootstraptabs'); ?>
" target="_blank" ><?php _e('Plugin Parent Site','bootstraptabs'); ?></a></li>
                <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LM2BSQJZ6JL56" title="<?php _e('Donate if you liked the plugin and support in enhancing WP Bootstrap Tabs and creating new plugins','bootstraptabs'); ?>" target="_blank" ><?php _e('Donate with Paypal','bootstraptabs'); ?></a></li>
                </ul> 
			  </div>	
			  
			  <div style="float:right;width:45%;">
				<div style="margin-top:10px;margin-bottom:5px;float:right;">
			    <a href="http://tabbervilla.com/" target="_blank" rel="nofollow"><img src="<?php echo bootstraptabs_plugin_url('images/virtus_logo_wp.png');?>" width="100%"/></a>
				</div>
				<div class="clear"></div>
				<a style="margin-top:10px;float:right;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LM2BSQJZ6JL56" target="_blank" rel="nofollow"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" width="100%"/></a>
			  </div>
			  <div class="clear"></div>
				
              </div> 
			</div> 

			<div class="clear"></div>
			

				<div style="margin:0 auto 15px auto;">
							<a href="http://virtusdesigns.com" title="Websites from Virtus Designs" target="_blank"><img src="<?php echo bootstraptabs_plugin_url('images/virtus-ad-300x250.jpg');?>" alt="Websites from Virtus Designs" width="100%" /></a>
				</div>


           <div class="postbox"> 
				<h3 class="hndle"><span></span><?php _e('Recommended WP Plug-in','bootstraptabs'); ?></h3>
     		  <div class="inside">
				<div style="margin:10px auto;">
							<a href="http://ithemes.com/member/go.php?r=3959&i=b16" target="_blank" title="Backup and restore your site with Backupbuddy"><img src="<?php echo bootstraptabs_plugin_url('images/backupbuddy-img.png');?>" border=0 alt="Backup WordPress Easily" width="100%" ></a>
				</div>
            </div></div>
                
     
     		<div class="postbox"> 
			  <h3 class="hndle"><span></span><?php _e('Recommended Service','bootstraptabs'); ?></h3> 
			  <div class="inside">
                     <div style="margin:10px 5px">
                        <a href="http://affl.sucuri.net/?affl=0b85e144dda7b18e2436c7578e2c1958" target="_blank" title="Website Protection from Sucuri"><img src="<?php echo bootstraptabs_plugin_url('images/sucuri-img.png');?>" alt="Sucuri Security" width="100%"/></a>
                     </div>
               </div></div>
</div> <!--end of poststuff -->
</form>

</div> <!--end of float wrap -->

<?php	
}
// Hook for adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'bootstraptabs_settings');
  add_action( 'admin_init', 'register_bootstraptabs_settings' ); 
} 
function register_bootstraptabs_settings() { // whitelist options
  register_setting( 'bootstraptabs-group', 'bootstraptabs_options' );
}
endif;
?>