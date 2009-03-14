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

function text_input_s ($name, $value, $size)
{
	return input ($name, $value, $size, "", "text");
}

// Typical password input for a form
function password_input ($name, $value)
{
	return input ($name, $value, 20, "", "password");
}

function password_input_s ($name, $value, $size)
{
	return input ($name, $value, $size, "", "password");
}

// Typical password input for a form
function submit_input ($value)
{
	return input ("formsubmit", $value, "", "", "submit");
}

// Input for a form that has more freedom
function input ($name, $value, $size, $id, $type)
{
	if (empty ($size))
		return "<input name=\"$name\" id=\"$id\" type=\"$type\" value=\"$value\">\n";
	else
		return "<input name=\"$name\" size=\"$size\" id=\"$id\" type=\"$type\" value=\"$value\">\n";
}


?>