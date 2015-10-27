// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('BOOTSTRAPTABS');
	
	tinymce.create('tinymce.plugins.BOOTSTRAPTABS', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {


			// Register example button
			ed.addButton('BOOTSTRAPTABS', {
				title : 'Add Bootstrap Tabs',
				cmd : 'mceBOOTSTRAPTABS',
				image : url + '/tab.png',
				onclick : function() {  
                     ed.selection.setContent('[bootstrap_tab name="Tab 1" link="tab1-slug" active="active"]Content for Tab 1[/bootstrap_tab]<br>[bootstrap_tab name="Tab 2" link="tab2-slug" ]Content for Tab 2[/bootstrap_tab]<br>[bootstrap_tab name="Tab 3" link="tab3-slug"]Content for Tab 3[/bootstrap_tab]<br>[end_bootstrap_tab]');    
                }
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'BOOTSTRAPTABS',
					author 	  : 'Virtusdesigns',
					authorurl : 'http://www.virtusdesigns.com/',
					infourl   : 'http://virtusdesigns.com/wordpress-bootstrap-tabs/',
					version   : "1.0.1"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('BOOTSTRAPTABS', tinymce.plugins.BOOTSTRAPTABS);
})();