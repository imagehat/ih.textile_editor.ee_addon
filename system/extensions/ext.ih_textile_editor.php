<?php
/*
	
    Textile Editor Helper (TEH) extension for ExpressionEngine Version 1.2.0 (build 20090328)

    EE extension by Mike Kroll, www.imagehat.com
    Port of Textile Editor by Dave Olson, slateinfo.blogs.wvu.edu

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
	var $version		= '1.2.0';
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
	 * Activate extension
	 *
	 **/
    function activate_extension()
    {
    	global $DB, $PREFS;
    	
    	$default_settings = serialize( $this->default_settings() );
    	
    	$DB->query($DB->insert_string('exp_extensions',
    								  array('extension_id'	=> '',
    										'class'			=> get_class($this),
    										'method'		=> "add_header",
    										'hook'			=> "publish_form_headers",
    										'settings'		=> $default_settings,
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
	
	/**
	 * Extension settings
	 *
	 **/
    function settings()
    {
    	global $PREFS;
    	
    	$settings = array();
    	
		$settings['teh_path']	  = '';
		$settings['help_url']     = '';
		$settings['encode_email'] = array('r', array('yes' => "yes", 'no' => "no"), 'no');
    	   	
    	return $settings;
    }
    // END settings
    
    
    /**
	 * Default Extension settings
	 *
	 * @since version 1.2.0
	 **/
    function default_settings()
    {
    	global $PREFS;
    	
    	$default_settings = array(
    	    'teh_path'	   => $PREFS->ini('theme_folder_url').'teh_themes/',
    		'help_url'     => 'http://hobix.com/textile/',
    		'encode_email' => 'no'
    	);
    	   	
    	return $default_settings;
    }
    // END Default settings
	
}


/* End of file ext.ih_textile_editor.php */
/* Location: ./system/extensions/ext.ih_textile_editor.php */