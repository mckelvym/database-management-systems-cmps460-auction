<?php
include_once ("dbinfo.php");
include_once ("form_common.php");
include_once ("table_common.php");
include_once ("list_common.php");

  // Call at the beginning of a script to output html header information including navbar
function echo_header ()
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
	echo ul_wrap (li_wrap (href ("index.php", "HOME")).
		      li_wrap (href ("login.php", "LOGIN")));
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

function href ($url, $text)
{
	return "<a href=\"$url\">$text</a>\n";
}

?>
