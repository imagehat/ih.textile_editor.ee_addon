Textile Editor Helper (TEH) extension for ExpressionEngine Version 1.2.0 (build 20080331)

EE extension by Mike Kroll, www.imagehat.com
Port of Textile Editor by Dave Olson, slateinfo.blogs.wvu.edu

Version 1.2.0 - Now requires jQuery for the control panel extension, which
                is bundled in EE 1.6.5+ or available for download
                from http://www.ngenworks.com/software/ee/cp_jquery/
              - Fixed a bug in IE where using the custom link/email
                functions caused selected text to be duplicated.
              - Non-IE custom functions selections enhanced.
              - Fixed a bug where new installs caused an error if
                email settings were not saved first.
              - Changed the default "teh" directory location to the
                themes folder instead of site root for new installs. 
                (Path can be changed in the extension settings. Note that
                existing path settings will remain in tact when upgrading).
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

NOTE: This obviously requires the EE Textile plugin to be installed correctly,
and your custom field set up to use Textile formatting:
http://expressionengine.com/downloads/details/textile/
http://expressionengine.com/docs/cp/admin/weblog_administration/custom_fields_edit.html

------------------------------------------------------------------------------

To UPGRADE from an earlier version:
1. Replace the extension and language file in your system folder.
2. Replace the "teh" directory.
3. Verify the your settings in the extension manager.

To INSTALL:
1. Upload the extension to system > extensions
2. Upload the language file to system > language > english
3. Upload the "teh" folder to your themes folder. 
   This folder contains all the javascripts, css, and images used.
4. Activate the extension (Admin > Utilities > Extension Manager)
5. Check the Extension Settings - and make sure the paths are correct.
6. Optional: You can place the "teh" directory anywhere you wish as long as
   you update the paths accordingly in the Extension Settings.
