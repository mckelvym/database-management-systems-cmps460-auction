<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$mode = get ("mode");
$title = get ("title");
$seller = get ("seller");
$category = get ("category");
$end_day = get ("end_day");
$end_hour = get ("end_hour");
$end_min = get ("end_minute");

if (!$dbinfo->logged_in ())
	redirect ("index.php");

cout ("Coming soon, current mode = '$mode'");




echo_footer ($dbinfo);

?>
