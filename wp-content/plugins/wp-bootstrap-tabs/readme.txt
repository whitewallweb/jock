=== WP Bootstrap Tabs ===
Contributors: Virtus Designs
Tags:post, page, tabs, options, shortcode, navigation, jquery
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LM2BSQJZ6JL56
Requires at least: 3.0
Tested up to: 3.6
Stable tag: 1.0.4
License: GPLv2

Add clean and structured twitter bootstrap tabs to your WordPress Posts and Pages easily through the edit post/page panel.

== Description ==

WP Bootstrap Tabs is a plugin for WordPress using the built in capabilities of Twitter Bootstrap. It is very lightweight designed to simply add tabs using the Bootstrap tabs structure.  They can be customized by adding your own styling or by modifying the styles and settings within the Bootstrap stylesheets and JQuery settings. For more information on how to do that, visit http://twitter.github.com/bootstrap/javascript.html#tabs

=Features:=

* Adds tabs using the out of the box styling and functionality in Twitter Bootstrap

== Installation ==

There's 3 ways to install this plugin:

= 1. The easy way =
1. Download the plugin (.zip file)
2. In your Admin, go to menu Plugins > Add
3. Select the tab "Upload"
4. Upload the .zip file you just downloaded
5. Activate the plugin
6. A new sub-menu under Settings menu named `WP Bootstrap Tabs` will appear in your Admin

= 2. The old way (FTP) =
1. Upload `wordPress-bootstrap-tabs` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. A new sub-menu under Settings menu named `WP Bootstrap Tabs` will appear in your Admin

== Usage ==

**Option 1 - Quick Tag**

* Use Quick Tags to get the plugin shortcode in the post/page content area. Fill the Quick Tag box with name of tabs (commna separated) and click on 'Insert' button. It will insert shortcode with default tab content, you can edit the content in the same edit panel of post/page.

**Option 2 - Manual Insertion of Shortcode**

* To insert the tabs, you would need to use two shortcodes on your Edit Post/Page admin panel. The first shortcode is for tab names, tab ID/slug, and setting the active tab. The second shortcode represents the end of the set of the tabs. You can insert multiple number of tabset on a single page.
* Example how to put tabs:


[bootstrap_tab name='Tab 1' link='tab1-slug' active='active']Content for the tab Tab 1[/bootstrap_tab]

[bootstrap_tab name='Tab 2' link='tab2-slug' ]Content for the tab Tab 2[/bootstrap_tab]

[bootstrap_tab name='Tab 3' link='tab3-slug']Content for the tab Tab 3[/bootstrap_tab]

[end_bootstrap_tab]


**Do not forget to insert the 'name' attribute for each tab. It is important** 
** Just remember to put the 'end_bootstrap_tab' shortcode at the end of all tab contents as shown above.**
* 'name' is the title of your tab, yo can put anything you want in here.  'link' is the html link and the content anchor to match up the tab list item with the content div.  This should not contain any spaces.  'active' is only needed for the tab you want to display when the pages is loaded.

== Screenshots ==

1. Usage on Edit Post Panel


== Frequently Asked Questions ==

None at this point

== Upgrade Notice ==

Please use the contact form in case of any issues while upgrading.

== Changelog ==

Version 1.0.4 (08/09/2013
1. Adjusted the priority settings of wpautop to run after the shortcode
2. Added a missing closing </div> tag at the end of the tab content

Version 1.0.2 (02/16/2013)
1. Initial Release


Visit the plugin page (http://virtusdesigns.com/wp-bootstrap-tabs) to see the changelog and release notes.