<?php
include_once ("dbinfo.php");
include_once ("form_common.php");
include_once ("table_common.php");
include_once ("list_common.php");

// Call at the beginning of a script to output html header information including navbar
function echo_header ($dbinfo)
{
	$dbinfo->init ();
	if (!$dbinfo->connect ())
		die ("Couldn't connect to database.");
	$dbinfo->update_time ();
	$dbinfo->update_closed_item_listings ();

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

	// HEADER AREA
	echo_div ("header");
	echo "iBay";
	end_div ();

	// STATUS AREA
	echo_div ("status");
	$day = $dbinfo->day () + 0;
	$hour = $dbinfo->hour ();
	$minute = $dbinfo->minute ();
	if ($dbinfo->logged_in ())
	{
		$realname = $dbinfo->realname ();
		$username = $dbinfo->username ();
		$logoutlink = href ("logout.php", "Logout");
		cout ("Welcome, $realname. [$username] $logoutlink");
	}
	else
	{
		$loginlink = href ("login.php", "Login");
		$reglink= href ("registration.php", "Register");
		cout ("Welcome! $loginlink or $reglink");
	}
	echo ("Day: $day, Time: $hour:$minute");
	if ($dbinfo->is_admin ())
	{
		echo "<br/>[Box to increase the time]";
	}
	end_div ();

	// NAVIGATION AREA
	echo_div ("navbar");
	if ($dbinfo->logged_in ())
	{
		if ($dbinfo->is_admin ())
			$nav = "Admin Panel<br/>";
		else
			$nav = "User Panel<br/>";

		if (current_script () != "index.php")
			$nav = $nav.li_wrap (href ("index.php", "Home"));
		$nav = $nav.li_wrap (href ("registration.php?mode=view", "Account"));
		$nav = $nav.li_wrap (href ("profile.php", "Profile"));
		$nav = $nav.li_wrap (href ("profile.php?mode=browse", "All Profiles"));
		if ($dbinfo->is_admin ())
			$nav = $nav.li_wrap (href ("registration.php?mode=browse", "All Accounts"));

		$nav = $nav.li_wrap (href ("category.php", "All Categories"));
		$nav = $nav.li_wrap (href ("category.php?view=art", " - Art"));
		$nav = $nav.li_wrap (href ("category.php?view=books", " - Books"));
		$nav = $nav.li_wrap (href ("category.php?view=clothes", " - Clothes"));
		$nav = $nav.li_wrap (href ("category.php?view=collectibles", " - Collectibles"));
		$nav = $nav.li_wrap (href ("category.php?view=electronics", " - Electronics"));
		$nav = $nav.li_wrap (href ("category.php?view=entertainment", " - Entertainment"));
		$nav = $nav.li_wrap (href ("category.php?view=jewelry", " - Jewelry"));
		$nav = $nav.li_wrap (href ("category.php?view=sporting_goods", " - Sporting Goods"));
		$nav = $nav.li_wrap (href ("category.php?view=toys", " - Toys"));
		$nav = $nav.li_wrap (href ("itemlisting.php?mode=new", "Create Auction"));

		if ($dbinfo->is_admin ())
		{
			$nav = $nav.li_wrap ("Database:");
			$nav = $nav.li_wrap (href ("viewtable.php?name=user", "User Table"));
			$nav = $nav.li_wrap (href ("viewtable.php?name=user_activity", "Activity Table"));
			$nav = $nav.li_wrap (href ("viewtable.php?name=item_listing", "Auction Table"));
			$nav = $nav.li_wrap (href ("viewtable.php?name=bids_on", "Bid Table"));
		}
		echo ul_wrap ($nav);
	}
	else
	{
		$nav = "Visitor<br/>";
		if (current_script () != "index.php")
			$nav = $nav.li_wrap (href ("index.php", "Home"));
		if (current_script () != "login.php")
			$nav = $nav.li_wrap (href ("login.php", "Login"));
		if (current_script () != "registration.php")
			$nav = $nav.li_wrap (href ("registration.php", "Register"));
		echo ul_wrap ($nav);
	}
	end_div ();
	echo_div ("content");
	echo "<!-- END HEADER -->\n";
}

// Call at the end of a script for closing html footer information
function echo_footer ($dbinfo)
{
	echo "<!-- FOOTER -->\n";
	end_div ();
	end_div ();
	echo <<<HEREDOC
</body>
</html>

HEREDOC;
	$dbinfo->close ();
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
//	header ("Location: $url");
	echo <<<HEREDOC
<script type="text/javascript">
<!--
window.location = "$url"
//-->
</script>
HEREDOC;

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

// Heading level 1
function h1 ($text)
{
	return "<h1>$text</h1>\n";
}

// an echo with a <br/> at the end
function cout ($str)
{
	echo "$str"."<br/>\n";
}

?>
