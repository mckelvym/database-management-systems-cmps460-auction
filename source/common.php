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

// Common include script which includes other utility scripts. Also has other
// common functions such as echoing the html headers and footers, html
// wrapping functions, and some formatting functions.




include_once ("dbinfo.php");
include_once ("form_common.php");
include_once ("table_common.php");
include_once ("list_common.php");

// Call at the beginning of a script to output html header information including
// navbar
function echo_header ($dbinfo)
{
	$dbinfo->init ();
	if (!$dbinfo->connect ())
		die ("Couldn't connect to database.");
	$dbinfo->update_all ();
	$site_time = post ("site_time");
	if (!empty ($site_time))
	{
		$post_site_time = post ("site_time");
		$post_curr_day = strtok ($post_site_time, " ");
		$post_curr_hour = strtok (" ");
		$post_curr_minute = strtok (" ");
		$user = $dbinfo->username ();
		$formatted_time = format_time ($post_curr_day, $post_curr_hour, $post_curr_minute);
		$dbinfo->query ("insert into user_activity values (
'$user', $post_curr_day, $post_curr_hour, $post_curr_minute, 'Updated website time to $formatted_time')");
		$dbinfo->update_all ();
	}

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
	if ($dbinfo->is_admin () && current_script () == "index.php")
	{
		$curr_day = $dbinfo->day ();
		$curr_hour = $dbinfo->hour ();
		$options = "";
		for ($i = $curr_day; $i <= $curr_day + 7; $i++)
		{
			for ($j = ($i == $curr_day)? $curr_hour + 1 : 0; $j < 24; $j++)
			{
				$options = $options.option ("$i $j 0", format_time ($i, $j, 0));
			}
		}
		echo form_begin ("$current_script", "post");
		echo select ("site_time", $options);
		echo submit_input ("Do it").alert ("This box allows you to advance the website time.", "?");
		echo form_end ();
//		echo "<br/>[Box to increase the time]";
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

/* 		if (current_script () != "index.php") */
		$nav = $nav.li_wrap (href ("index.php", "Home"));
		$nav = $nav.li_wrap (href ("registration.php?mode=view", "Account"));
		$nav = $nav.li_wrap (href ("profile.php", "Profile"));
		$nav = $nav.li_wrap (href ("profile.php?mode=browse", "All Profiles"));
		if ($dbinfo->is_admin ())
			$nav = $nav.li_wrap (href ("registration.php?mode=browse", "All Accounts"));

		$nav = $nav.li_wrap (href ("category.php", "All Categories"));
		$nav = $nav.li_wrap (href ("category.php?view=Art", " - Art"));
		$nav = $nav.li_wrap (href ("category.php?view=Books", " - Books"));
		$nav = $nav.li_wrap (href ("category.php?view=Clothing", " - Clothing"));
		$nav = $nav.li_wrap (href ("category.php?view=Collectibles", " - Collectibles"));
		$nav = $nav.li_wrap (href ("category.php?view=Electronics", " - Electronics"));
		$nav = $nav.li_wrap (href ("category.php?view=Entertainment", " - Entertainment"));
		$nav = $nav.li_wrap (href ("category.php?view=Jewelry", " - Jewelry"));
		$nav = $nav.li_wrap (href ("category.php?view=Sporting Goods", " - Sporting Goods"));
		$nav = $nav.li_wrap (href ("category.php?view=Toys", " - Toys"));
		$nav = $nav.li_wrap (href ("itemlisting.php?mode=new", "Create Auction"));

		if ($dbinfo->is_admin ())
		{
			$nav = $nav.li_wrap ("Database:");
			$nav = $nav.li_wrap (href ("viewtable.php?name=user", "User Table"));
			$nav = $nav.li_wrap (href ("viewtable.php?name=user_activity", "Activity Table"));
			$nav = $nav.li_wrap (href ("viewtable.php?name=item_listing", "Auction Table"));
			$nav = $nav.li_wrap (href ("viewtable.php?name=bids_on", "Bid Table"));
			$nav = $nav.li_wrap (href ("viewtable.php?name=reload", "Reload DB"));
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

// Wrap text in a div
function div ($text, $style_name = "")
{
	if (!empty ($style_name))
		$style_name = "id=\"$style_name\"";
	return "<div $style_name>\n$text\n</div>\n";
}

// Wrap text in a span
function span ($text, $style_name)
{
	if (!empty ($style_name))
		$style_name = "id=\"$style_name\"";
	return "<span $style_name>\n$text\n</span>\n";
}

// make a quick link
function href ($url, $text)
{
	return "<a href=\"$url\">$text</a>";
}

// make a quick link
function hreft ($url, $text, $target)
{
	return "<a href=\"$url\" target=\"$target\">$text</a>";
}

// Quick html image code
function img ($path)
{
	return "<img src=\"$path\">";
}

// Quick html image code for local images
function local_img ($path)
{
	return "<img id=dynimg src=\"images/$path\">";
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
	if (isset ($_GET[$key]))
		$val = $_GET[$key];
	else
		$val = "";
	return $val;
}

// Get from the POST variable
function post ($key)
{
	if (isset ($_POST[$key]))
		$val = $_POST[$key];
	else
		$val = "";
	return $val;
}

// Script to make setting session variables easier.
function session_set ($key, $val)
{
	$key = "tmp_$key";
	$_SESSION[$key] = $val;
}

// Script to make getting session variables easier.
function session_get ($key)
{
	$key = "tmp_$key";
	return $_SESSION[$key];
}

// Heading level 1
function h1 ($text)
{
	return "<h1>$text</h1>\n";
}

// Heading level 2
function h2 ($text)
{
	return "<h2>$text</h2>\n";
}

// Heading level 3
function h3 ($text)
{
	return "<h3>$text</h3>\n";
}

// an echo with a <br/> at the end
function cout ($str)
{
	echo "$str"."<br/>\n";
}

// Format time to readable format
function format_time ($day, $hour, $minute, $prefix_zeros_to_day = false)
{
	$day += 0;
	$hour += 0;
	$minute += 0;

	if ($minute > 59)
	{
		$minute -= 60;
		$hour++;
	}

	if ($hour > 23)
	{
		$hour -= 24;
		$day++;
	}

	if ($minute < 10)
	{
		$minute = "0".$minute;
	}

	if ($hour < 10)
	{
		$hour = "0".$hour;
	}

	if ($prefix_zeros_to_day)
	{
		if ($day < 10)
		{
			$day = "0000".$day;
		}
		else if ($day < 100)
		{
			$day = "000".$day;
		}
		else if ($day < 1000)
		{
			$day = "00".$day;
		}
		else if ($day < 10000)
		{
			$day = "0".$day;
		}
	}

	return "Day: $day, Time: $hour:$minute";
}

// Pad time for sorting on the home page
function pad_time ($day, $hour, $minute, $priority = 0)
{
	if ($minute > 59)
	{
		$minute -= 60;
		$hour++;
	}

	if ($hour > 23)
	{
		$hour -= 24;
		$day++;
	}

	if ($minute < 10)
	{
		$minute = "0".$minute;
	}

	if ($hour < 10)
	{
		$hour = "0".$hour;
	}

	if ($day < 10)
	{
		$day = "0000".$day;
	}
	else if ($day < 100)
	{
		$day = "000".$day;
	}
	else if ($day < 1000)
	{
		$day = "00".$day;
	}
	else if ($day < 10000)
	{
		$day = "0".$day;
	}

	return "$day$hour$minute$priority ";
}

// If time1 is older than time2, returns true
function is_older ($d1, $h1, $m1, $d2, $h2, $m2)
{
	if (($d1 < $d2) ||
	    ($d1 == $d2 && $h1 < $h2) ||
	    ($d1 == $d2 && $h1 == $h2 && $m1 < $m2))
		return true;
	return false;
}

// Strip quotes from a string (for form input)
function fix_quotes ($str)
{
	return str_replace ("\'", "", $str);
}

// Escape quotes in a string
function escape ($val)
{
	return mysql_real_escape_string ($val);
}

// Embed html link for javascript alert
function alert ($msg, $linktext)
{
	return "<a href=\"javascript:alert ('$msg')\">$linktext</a>";
}

?>
