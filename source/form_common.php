<?php

// Action: script to execute
// Method: POST or GET
function form_begin ($action, $method)
{
	return "<form action=\"$action\" method=\"$method\">\n";
}

function form_end ()
{
	return "</form>\n";
}

// Typical text input for a form
function text_input ($name, $value)
{
	return input ($name, $value, 20, "", "text");
}

function text_input_s ($name, $value, $size, $maxsize)
{
	return input ($name, $value, $size, "", "text", $maxsize);
}

function text_input_sr ($name, $value, $size, $maxsize)
{
	return input ($name, $value, $size, "", "text", $maxsize, "readonly");
}

// Typical password input for a form
function password_input ($name, $value)
{
	return input ($name, $value, 20, "", "password");
}

function password_input_s ($name, $value, $size, $maxsize)
{
	return input ($name, $value, $size, "", "password", $maxsize);
}

// Typical password input for a form
function submit_input ($value)
{
	return input ("formsubmit", $value, "", "", "submit");
}

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

function select ($name, $options)
{
	return "<select name=\"$name\">\n$options \n</select>";
}

function option ($value, $display, $is_selected = "")
{
	if (!empty ($is_selected) && $value == $is_selected)
		return "<option selected=\"yes\" value=\"$value\">$display</option>\n";
	else
		return "<option value=\"$value\">$display</option>\n";
}


?>