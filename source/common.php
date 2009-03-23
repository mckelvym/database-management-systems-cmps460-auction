<?php
include_once ("dbinfo.php");
include_once ("form_common.php");
include_once ("table_common.php");
include_once ("list_common.php");

// Call at the beginning of a script to output html header information including navbar
function echo_header ($dbinfo)
{
	echo <<<HEREDOC
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CMPS 460 DB Project</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

HEREDOC;
	echo_div ("container");
	echo_div ("navbar");
	if (!$dbinfo->logged_in ())
	{
		$nav = "";
		if (current_script () != "index.php")
			$nav = $nav.li_wrap (href ("index.php", "Home"));
		if (current_script () != "login.php")
			$nav = $nav.li_wrap (href ("login.php", "Login"));
		if (current_script () != "register.php")
			$nav = $nav.li_wrap (href ("register.php", "Register"));

		echo ul_wrap ($nav);
	}
	else
	{
		echo ul_wrap (li_wrap ($dbinfo->name ()." (".$dbinfo->user ().")"));
		$nav = "";
		if (current_script () != "index.php")
			$nav = $nav.li_wrap (href ("index.php", "Home"));
		$nav = $nav.li_wrap (href ("logout.php", "Logout"));
		echo ul_wrap ($nav);
	}
	end_div ();
	echo_div ("content");
	echo "<!-- END HEADER -->\n";
}

// Call at the end of a script for closing html footer information
function echo_footer ()
{
	echo "<!-- FOOTER -->\n";
	end_div ();
	end_div ();
	echo <<<HEREDOC
</body>
</html>

HEREDOC;
}

// determine the current executing script
function current_script ()
{
	return basename ($_SERVER["SCRIPT_NAME"]);
}

// Quick echo of a special div area
function echo_div ($name)
{
	if (empty ($name))
		echo "<div>\n";
	else
		echo "<div id=\"$name\">\n";
}

// Quick close div
function end_div ()
{
	echo "</div>\n";
}

// make a quick link
function href ($url, $text)
{
	return "<a href=\"$url\">$text</a>\n";
}

// Make the user go to a different page
function redirect ($url)
{
	header ("Location: $url");
}

// Get from the GET variable
function get ($key)
{
	$val = $_GET[$key];
	return $val;
}

// Get from the POST variable
function post ($key)
{
	$val = $_POST[$key];
	return $val;
}

?>
