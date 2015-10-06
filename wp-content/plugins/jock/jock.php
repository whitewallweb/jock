<?php
/*
   Plugin Name: Jock
   Plugin URI: http://wordpress.org/extend/plugins/jock/
   Version: 0.2
   Author: Ants
   Description: Jock template
   Text Domain: jock
   License: GPLv3
  */


   

$Jock_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function Jock_noticePhpVersionWrong() {
    global $Jock_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Jock" requires a newer version of PHP to be running.',  'jock').
            '<br/>' . __('Minimal version of PHP required: ', 'jock') . '<strong>' . $Jock_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'jock') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function Jock_PhpVersionCheck() {
    global $Jock_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $Jock_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'Jock_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function Jock_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('jock', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','Jock_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (Jock_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('jock_init.php');
    Jock_init(__FILE__);
}
