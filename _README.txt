
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

NOTE: This obviously requires the EE Textile plugin to be installed correctly,
and your custom field set up to use Textile formatting:
http://expressionengine.com/downloads/details/textile/
http://expressionengine.com/docs/cp/admin/weblog_administration/custom_fields_edit.html

------------------------------------------------------------------------------

To UPGRADE from an earlier version:
1. I recommend just disabling the old version, and deleting the extension,
   the language file, and the exisiting "teh" directory first. There are only
   a few settings in the new version, and the extension file has been renamed
   with a unique prefix as recommended by EllisLab.
2. Install the new version and verify the new settings as below.

To INSTALL:
1. Upload the extension to system > extensions
2. Upload the language file to system > language > english
3. Upload the "teh" folder to your site root. 
   This folder contains all the javascripts, css, and images used.
4. Activate the extension (Admin > Utilities > Extension Manager)
5. Check the Extension Settings - and make sure the paths are correct.
6. Optional: You can place the "teh" directory anywhere you wish as long as
   you update the paths accordingly in the Extension Settings.
7. Optional: If you're already using jQuery elsewhere in your Control Panel
   you can update the path in the Extension Settings to point to your existing
   file instead of using the one I've bundled. Tested with jQuery 1.2.6.
   