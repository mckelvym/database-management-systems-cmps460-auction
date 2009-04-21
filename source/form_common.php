<?php
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

// Form related common functions





// Action: script to execute
// Method: POST or GET
function form_begin ($action, $method)
{
	return "<form action=\"$action\" method=\"$method\">\n";
}

// Same as above but is names and has javascript onsubmit
function form_begin_n ($action, $method, $name, $on_submit)
{
	return "<form action=\"$action\" method=\"$method\" accept-charset=\"UTF-8\" name=\"$name\" onsubmit=\"$on_submit\">\n";
}

// End form
function form_end ()
{
	return "</form>\n";
}

// Typical text input for a form
function text_input ($name, $value)
{
	return input ($name, $value, 20, "", "text");
}

// Typical text input for a form with size
function text_input_s ($name, $value, $size, $maxsize)
{
	return input ($name, $value, $size, "", "text", $maxsize);
}

// Typical text input for a form with size and readonly
function text_input_sr ($name, $value, $size, $maxsize)
{
	return input ($name, $value, $size, "", "text", $maxsize, "readonly");
}

// Typical password input for a form
function password_input ($name, $value)
{
	return input ($name, $value, 20, "", "password");
}

// Typical password input for a form with size
function password_input_s ($name, $value, $size, $maxsize)
{
	return input ($name, $value, $size, "", "password", $maxsize);
}

// Typical submit input for a form
function submit_input ($value)
{
	return input ("formsubmit", $value, "", "", "submit");
}

// Hidden input for a form
function hidden_input ($name, $value)
{
	return "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
}

// Input for a form that has more freedom
function input ($name, $value, $size, $id, $type, $maxsize = "", $other_opts = "")
{
	if (!empty ($maxsize))
		$maxsize = "maxlength=\"$maxsize\"";
	if (empty ($size))
		return "<input name=\"$name\" $maxsize id=\"$id\" type=\"$type\" value=\"$value\" $other_opts>\n";
	else
		return "<input name=\"$name\" $maxsize size=\"$size\" id=\"$id\" type=\"$type\" value=\"$value\" $other_opts>\n";
}

// Form select which takes html string of options
function select ($name, $options)
{
	return "<select name=\"$name\">\n$options \n</select>";
}

// Dynamic select used for javascript
function select_dyn ($name, $options, $javascript_func)
{
	return "<select onChange=\"$javascript_func\" id=\"dyn$name\" name=\"$name\">\n$options \n</select>";
}

// Html option used for a select dropdown
function option ($value, $display, $is_selected = "")
{
	if (!empty ($is_selected) && $value == $is_selected)
		return "<option selected=\"yes\" value=\"$value\">$display</option>\n";
	else
		return "<option value=\"$value\">$display</option>\n";
}
?>