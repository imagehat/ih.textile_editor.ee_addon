// -------------------------------------------------------------
//  Standard TEH buttons
// -------------------------------------------------------------
//  Delete or comment out the ones you want to disable.

var teButtons = jQuery.TextileEditor.buttons;

teButtons.push(new TextileEditorButton('ed_strong', 'bold.png', '*', '*', 'b', 'Bold','s'));
teButtons.push(new TextileEditorButton('ed_emphasis', 'italic.png', '_', '_', 'i', 'Italicize','s'));
teButtons.push(new TextileEditorButton('ed_underline', 'underline.png', '+', '+', 'u', 'Underline','s'));
teButtons.push(new TextileEditorButton('ed_strike', 'strikethrough.png', '-', '-', 's', 'Strikethrough','s'));
teButtons.push(new TextileEditorButton('ed_ol', 'list_numbers.png', '# ', '\n', ',', 'Numbered List'));
teButtons.push(new TextileEditorButton('ed_ul', 'list_bullets.png', '* ', '\n', '.', 'Bulleted List'));
teButtons.push(new TextileEditorButton('ed_p', 'paragraph.png', 'p', '\n', 'p', 'Paragraph'));
teButtons.push(new TextileEditorButton('ed_h1', 'h1.png', 'h1', '\n', '1', 'Header 1'));
teButtons.push(new TextileEditorButton('ed_h2', 'h2.png', 'h2', '\n', '2', 'Header 2'));
teButtons.push(new TextileEditorButton('ed_h3', 'h3.png', 'h3', '\n', '3', 'Header 3'));
teButtons.push(new TextileEditorButton('ed_h4', 'h4.png', 'h4', '\n', '4', 'Header 4'));
teButtons.push(new TextileEditorButton('ed_block', 'blockquote.png', 'bq', '\n', 'q', 'Blockquote'));
teButtons.push(new TextileEditorButton('ed_outdent', 'outdent.png', ')', '\n', ']', 'Outdent'));
teButtons.push(new TextileEditorButton('ed_indent', 'indent.png', '(', '\n', '[', 'Indent'));
teButtons.push(new TextileEditorButton('ed_justifyl', 'left.png', '<', '\n', 'l', 'Left Justify'));
teButtons.push(new TextileEditorButton('ed_justifyc', 'center.png', '=', '\n', 'e', 'Center Text'));
teButtons.push(new TextileEditorButton('ed_justifyr', 'right.png', '>', '\n', 'r', 'Right Justify'));
teButtons.push(new TextileEditorButton('ed_justify', 'justify.png', '<>', '\n', 'j', 'Justify'));
//teButtons.push(new TextileEditorButton('[id]', '[image.png]', '[open]', '[close]', '[accesskey]', '[Title]', '[simple or extended]'));


// -------------------------------------------------------------
//  Custom button additions
// -------------------------------------------------------------
// Delete or comment out the ones you want to disable.

teButtons.push(new TextileEditorButtonSeparator(''));
teButtons.push("<button id=\"ed_link\" onclick=\"addLink(this, 'link');return false;\" class=\"standard\"><img src=\""+teh_options.image_path+"world_link.png\" title=\"Link\" alt=\"Link\" /></button>");
teButtons.push("<button id=\"ed_email\" onclick=\"addLink(this, 'email');return false;\" class=\"standard\"><img src=\""+teh_options.image_path+"email_link.png\" title=\"Email\" alt=\"Email\" /></button>");
teButtons.push("<button id=\"ed_help\" onclick=\"teh_help('"+teh_options.help_url+"');return false;\" class=\"standard\"><img src=\""+teh_options.image_path+"help.png\" title=\"Help\" alt=\"Help\" /></button>");


// Open Help link in new window
// Called from custom Help button
function teh_help(url) {
	window.open(url, "_blank");
	return false;
}

// Insert link into text field. 
// Called from linkDialog
function addLink(button, id)
{
	var myField = document.getElementById(button.canvas);
	myField.focus();

	// Selection testing straight from TEH ---------------------
	var textSelected = false;
	var finalText = '';
	var FF = false;

	// grab the text that's going to be manipulated, by browser
	if (document.selection) { // IE support
		
		sel = document.selection.createRange();
		
		// set-up the text vars
		var beginningText = '';
		var followupText = '';
		var selectedText = sel.text;

		// check if text has been selected
		if (sel.text.length > 0) {
			textSelected = true;	
		}

	}
	else if (myField.selectionStart || myField.selectionStart == '0') { // MOZ/FF/NS/S support
		
		// figure out cursor and selection positions
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		var cursorPos = endPos;
		var scrollTop = myField.scrollTop;
		FF = true; // note that is is a FF/MOZ/NS/S browser

		// set-up the text vars
		var beginningText = myField.value.substring(0, startPos);
		var followupText = myField.value.substring(endPos, myField.value.length);

		// check if text has been selected
		if (startPos != endPos) {
			textSelected = true;
			var selectedText = myField.value.substring(startPos, endPos);	
		}
	}
	// End selection testing -----------------------------------
	
	// Prompt user and build URL link
	if (id == 'link') {
		
		var link = prompt("Enter a URL:", "http://");
		if (link == "http://" || link == "" || link == null) return false;
		
		if (textSelected) {
			link = '"'+selectedText+'":'+link;	
		} else {
			link = '"Text Here":'+link+' ';
		}
	}
	
	// Prompt user and build email link
	if (id == 'email') {
		
		var link = prompt("Enter an email address:", "");
		if (link == "" || link == null) return false;

		// Check for encoding option and build link
		if (teh_options.encode_email === 'yes')
		{
			if (textSelected) {
				link = '['+'email='+link+']'+selectedText+'[/email]';
			} else {
				link = '[email]'+link+'[/email]';
			}
		} else {
			if (textSelected) {
				link = '"'+selectedText+'":mailto:'+link;
			} else {
				link = '"'+link+'":mailto:'+link;
			}
		}
	}

	// set the appropriate DOM value with the final text
	if (FF == true) {
		finalText = beginningText+link+followupText;
		myField.value = finalText;
		myField.scrollTop = scrollTop;
	}
	else {
		finalText = link;
		sel.text = finalText;
	}
	
	// build up the selection capture, doesn't work in IE
	if (textSelected) {
		myField.selectionStart = startPos;
		myField.selectionEnd = startPos+link.length;
	}
	else {
		myField.selectionStart = cursorPos+link.length;
		myField.selectionEnd = cursorPos+link.length;
	}
	
	jQuery(button).addClass('unselected');
}
// END custom functions