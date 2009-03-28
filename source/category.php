<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$view = get ("view");
$sortby = get ("sortby");

if (!$dbinfo->logged_in ())
	redirect ("index.php");

cout ("Coming soon, currently viewing = '$view'");




echo_footer ($dbinfo);

?>
