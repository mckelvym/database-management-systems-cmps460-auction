/*
CMPS460 Database Project
Group I
April 20, 2009

Authors:
 - Trey Alexander 	(txa4895)
 - Dallas Griffith 	(dlg5367)
 - Mark McKelvy 	(jmm0468)
 - Sayooj Valsan 	(sxv6633)

~~~ CERTIFICATION OF AUTHENTICITY ~~~
The code contained within this script is the combined work of the above mentioned authors.
*/

function reset_inputs (thisform)
{
	// reset inputs
	// http://webcheatsheet.com/javascript/form_validation.php
	for each (elem in thisform.elements)
	{
		if (elem.type == "text" || elem.type == "select-one")
		{
			elem.style.background = '';
		}
	}
}

function validate_required (field)
{
	// http://www.w3schools.com/jS/js_form_validation.asp
	// http://webcheatsheet.com/javascript/form_validation.php
	with (field)
	{
		if (value == null || value == "" || value == "-")
		{
			field.style.background = 'Yellow';
			return false;
		}
		else
		{
			return true;
		}
	}
}

function field_set (fieldvar, field)
{
	if (fieldvar == null)
		return field;
	else
		return fieldvar;
}

function append_with_newline (orig, append_text)
{
	if (orig != null)
		return orig + '\n' + append_text;
	return append_text;
}

function validate_form (thisform)
{
	var field_of_focus;
	var alert_message;

	reset_inputs (thisform);

	with (thisform)
	{
		if (validate_required (title) == false)
		{
			field_of_focus = field_set (field_of_focus, title);
			alert_message = append_with_newline (alert_message, "Auction title required.");
		}
		if (validate_required (description) == false)
		{
			field_of_focus = field_set (field_of_focus, description);
			alert_message = append_with_newline (alert_message, "Auction description required.");
		}

		if (alert_message != null)
		{
			alert (alert_message);
			field_of_focus.focus();
			return false;
		}
	}
}

function local_img (file)
{
	return "images/" + file;
}

function image_swap ()
{
	var image = document.getElementById ("dynimg");
	var cat = document.getElementById ("dyncategory");
	var pic = document.getElementById ("dynpicture");
	image.src = local_img (cat.value.toLowerCase ().replace (" ", "_") + pic.value);
}

/* onload = function() */
/* { */
/* 	image_swap (); */
/* } */

