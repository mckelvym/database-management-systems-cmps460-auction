<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$mode = get ("mode");

if (!$dbinfo->logged_in ())
	redirect ("index.php");

cout ("Coming soon, current mode = '$mode'");




echo_footer ($dbinfo);

?>
