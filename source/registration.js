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

function validate_email (field)
{
	// http://www.w3schools.com/jS/js_form_validation.asp
	// http://webcheatsheet.com/javascript/form_validation.php

	var email_filter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
	var illegal_chars= /[\(\)\<\>\,\;\:\\\"\[\]]/;
	var value = trim (field.value);

	if (value == null
		|| value == ""
		|| !email_filter.test (value)
		|| value.match (illegal_chars))
	{
		field.style.background = 'Yellow';
		return false;
	}
	return true;
}

function trim (string)
{
	// regex to replace one or more spaces at beginning of string
	// or one or more spaces at end of string, with empty string
	return string.replace (/^\s+|\s+$/, '');
}

function validate_int (field, length)
{
	with (field)
	{
		if (value.length < length || value * 1 == 0)
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

function validate_form (thisform)
{
	var field_of_focus;
	var alert_message;

	reset_inputs (thisform);

	with (thisform)
	{
		if (validate_required (username) == false)
		{
			field_of_focus = field_set (field_of_focus, username);
			alert_message = append_with_newline (alert_message, "Username required.");
		}
		if (validate_required (password) == false)
		{
			field_of_focus = field_set (field_of_focus, password);
			alert_message = append_with_newline (alert_message, "Password required.");
		}
		if (validate_required (password2) == false)
		{
			field_of_focus = field_set (field_of_focus, password2);
			alert_message = append_with_newline (alert_message, "Password confirmation required.");
		}
		if (password.value != password2.value)
		{
			field_of_focus = field_set (field_of_focus, password);
			alert_message = append_with_newline (alert_message, "Passwords must match.");
		}
		if (validate_required (realname) == false)
		{
			field_of_focus = field_set (field_of_focus, realname);
			alert_message = append_with_newline (alert_message, "Real name required.");
		}
		if (validate_required (birth_date) == false)
		{
			field_of_focus = field_set (field_of_focus, birth_date);
			alert_message = append_with_newline (alert_message, "Birth date required.");
		}
		else if ((birth_date.value.length >= 6) == false)
		{
			field_of_focus = field_set (field_of_focus, birth_date);
			alert_message = append_with_newline (alert_message, "Birth date too short.");
		}
		if (validate_required (shipping_street) == false)
		{
			field_of_focus = field_set (field_of_focus, shipping_street);
			alert_message = append_with_newline (alert_message, "Street required.");
		}
		if (validate_required (shipping_city) == false)
		{
			field_of_focus = field_set (field_of_focus, shipping_city);
			alert_message = append_with_newline (alert_message, "City required.");
		}
		if (validate_required (shipping_zip) == false)
		{
			field_of_focus = field_set (field_of_focus, shipping_zip);
			alert_message = append_with_newline (alert_message, "Postal/zip code required.");
		}
		else if (validate_int (shipping_zip, 5) == false) // this test works only for US codes
		{
			field_of_focus = field_set (field_of_focus, shipping_zip);
			alert_message = append_with_newline (alert_message, "Postal/zip code is not valid.");
		}
		if (validate_required (phone) == false)
		{
			field_of_focus = field_set (field_of_focus, phone);
			alert_message = append_with_newline (alert_message, "Phone number required.");
		}
		else if ((phone.value.length >= 10) == false)
		{
			field_of_focus = field_set (field_of_focus, phone);
			alert_message = append_with_newline (alert_message, "Phone number required.");
		}
		if (validate_required (email) == false)
		{
			field_of_focus = field_set (field_of_focus, email);
			alert_message = append_with_newline (alert_message, "E-mail required.");
		}
		else if (validate_email (email) == false)
		{
			field_of_focus = field_set (field_of_focus, email);
			alert_message = append_with_newline (alert_message, "E-mail address not valid.");
		}
		if (validate_required (card_number) == false)
		{
			field_of_focus = field_set (field_of_focus, card_number);
			alert_message = append_with_newline (alert_message, "Credit card number required.");
		}
		else if (validate_int (card_number, 12) == false)
		{
			field_of_focus = field_set (field_of_focus, card_number);
			alert_message = append_with_newline (alert_message, "Credit card number is too short.");
		}

		if (alert_message != null)
		{
			alert (alert_message);
			field_of_focus.focus();
			return false;
		}
	}
}
