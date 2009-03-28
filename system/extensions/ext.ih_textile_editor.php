<?php
/*
	
    Textile Editor Helper (TEH) extension for ExpressionEngine Version 1.1.0 (build 20080726)

    EE extension by Mike Kroll, www.imagehat.com
    Port of Textile Editor by Dave Olson, slateinfo.blogs.wvu.edu

    Version 1.1.0 - Ported js from Prototype to jQuery
                  - Added option to encode email addresses to use pMcode 
                    [email=email@address.com]Text Here[/email]
                    Defaults to a plain mailto link as before.
    Version 1.0.4 - Added help custom button and extension setting to define the url
    Version 1.0.3 - Fixed a bug in the original script where a space was added to the 
                    beginning of list items
    Version 1.0.2 - Added auto-toggling of TEH toolbar when formatting select dropdown is changed. 
                  - Cleaned up custom buttons javascript. Compressed prototype.js.
    Version 1.0.1 - Added custom buttons for links and email links
    Version 1.0.0 - Initial release

    ------------------------------------------------------------------------------	
    Textile Editor v0.2
    ------------------------------------------------------------------------------	
    created by: dave olsen, wvu web services
    created on: march 17, 2007
    project page: slateinfo.blogs.wvu.edu
    inspired by: 
     - Patrick Woods, http://www.hakjoon.com/code/38/textile-quicktags-redirect & 
     - Alex King, http://alexking.org/projects/js-quicktags
    ------------------------------------------------------------------------------

    Copyright (c) 2007 Dave Olsen, West Virginia University

    Permission is hereby granted, free of charge, to any person
    obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without
    restriction, including without limitation the rights to use,
    copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following
    conditions:

    The above copyright notice and this permission notice shall be
    included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
    OTHER DEALINGS IN THE SOFTWARE.

    ------------------------------------------------------------------------------

*/
if (!defined('EXT')){ exit('Invalid file request'); }


class Ih_textile_editor
{
	var $settings		= array();
	
	var $name			= 'Textile Editor Helper (TEH)';
	var $version		= '1.1.0';
	var $description	= 'Makes all Textareas set to use Textile formatting in the Publish area WYSIWYG-ish';
	var $settings_exist	= 'y';
	var $docs_url		= 'http://slateinfo.blogs.wvu.edu/plugins/textile_editor_helper/';
	
	/**
	 * Constructor
	 *
	 **/
	function Ih_textile_editor($settings='')
	{
		$this->settings = $settings;
	}
	// END
	
	/**
	 * Add javascript to head
	 *
	 **/
	function add_header()
	{
		global $EXT, $DB, $IN;
		
		// Play nice with others
		$js = ($EXT->last_call !== FALSE) ? $EXT->last_call : '';
        
        // Build the javascript
	    $js .= '
<!-- Textile Editor Extension -->
<script type="text/javascript">
//<![CDATA[
    
    // Define options
    teh_options = { 
        view: "extended",
        image_path: "'.trim($this->settings['teh_path']).'images/", 
        help_url: "'.trim($this->settings['help_url']).'",
        encode_email: "'.trim($this->settings['encode_email']).'"
    };

//]]>
</script>

<link rel="stylesheet" href="'.trim($this->settings['teh_path']).'stylesheets/textile-editor.css" type="text/css" media="screen">
<script type="text/javascript" src="'.trim($this->settings['jquery_url']).'"></script>
<script type="text/javascript" src="'.trim($this->settings['teh_path']).'javascripts/textile-editor.js"></script>
<script type="text/javascript" src="'.trim($this->settings['teh_path']).'javascripts/textile-editor-config.js"></script>

<script type="text/javascript">
//<![CDATA[
    
    $(document).ready(function($) {
    
        // Initialize textile fields without formatting menu
        $("input[name*=\'field_ft_\']").each(function(){
            if ($(this).val() == "textile") {
    			var canvas = "field_id_"+$(this).attr("name").substring(9);
    			$("#"+canvas).TextileEditor(teh_options);
    		}
        });
    
        // Initialize textile fields with formatting menu
    	$("select[name*=\'field_ft_\']").each(function(){
    		if ($(this).val() == "textile") {
    			var canvas = "field_id_"+$(this).attr("name").substring(9);
    			$("#"+canvas).TextileEditor(teh_options);
    		}			
    		// Toggle TEH
    		$(this).change(function() { 
    			var canvas = "field_id_"+this.name.substring(9);
    			var toolbar = $("#textile-toolbar-"+canvas);

    			if ($(this).val() == "textile") {
    				if (toolbar.length == 0) $("#"+canvas).TextileEditor(teh_options);
    			} else {
    				if (toolbar.length > 0) toolbar.remove();
    			}
    		});
    	});
    });

//]]>
</script>
<!-- END Textile Editor Extension -->

';
        
		return $js;
    }
    // END add header
  
    
    /**
	 * Extension settings
	 *
	 **/
    function settings()
    {
    	global $PREFS;
    	
    	$settings = array();
    	
		$settings['jquery_url']	  = $PREFS->ini('site_url', TRUE).'teh/javascripts/jquery.js';    	
		$settings['teh_path']	  = $PREFS->ini('site_url', TRUE).'teh/';
		$settings['help_url']     = 'http://hobix.com/textile/';
		$settings['encode_email'] = array('r', array('yes' => "yes", 'no' => "no"), 'no');
    	   	
    	return $settings;
    }
    // END settings

    
    /**
	 * Activate extension
	 *
	 **/
    function activate_extension()
    {
    	global $DB, $PREFS;
    	
    	$default_settings = $this->settings();
    	
    	$DB->query($DB->insert_string('exp_extensions',
    								  array('extension_id'	=> '',
    										'class'			=> get_class($this),
    										'method'		=> "add_header",
    										'hook'			=> "publish_form_headers",
    										'settings'		=> serialize($default_settings),
    										'priority'		=> 10,
    										'version'		=> $this->version,
    										'enabled'		=> "y"
    										)
    								 )
    			   );

    }
    // END activate
    
    
    /**
	 * Update extension
	 *
	 **/
    function update_extension($current='')
    {
    	global $DB, $PREFS;
    	
    	if ($current == '' OR $current == $this->version)
    	{
    		return FALSE;
    	}
    	
    	if ($current < '1.1.0')
    	{
    		// Kill the old version just in case (class was renamed in 1.1)
    		$sql[] = "DELETE FROM exp_extensions WHERE class = 'Textile_editor'";
    		
    		// Add new settings
    		$sql[] = "UPDATE exp_extensions SET settings = '" . addslashes(serialize($this->settings)) . "' WHERE class = '" . get_class($this) . "'";
    	}
    	
    	// Update version    	
    	$sql[] = "UPDATE exp_extensions SET version = '".$DB->escape_str($this->version)."' WHERE class = '" . get_class($this) . "'";
    	
    	// Run update queries
    	foreach ($sql as $query)
		{
			$DB->query($query);
		}		
    }
    // END update
    
    
    /**
	 * Disable extension
	 *
	 **/
    function disable_extension()
	{
		global $DB;
		
		$DB->query("DELETE FROM exp_extensions WHERE class = '" . get_class($this) . "'");
	}
	
}
// END Class

?>