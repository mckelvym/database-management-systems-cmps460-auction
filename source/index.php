<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
$dbinfo->init ();

echo_header ($dbinfo);

if (!$dbinfo->connect ())
{
	echo "Couldn't connect to database.";
	echo_footer ();
	return;
}
$dbinfo->update_time ();

echo "Current Time? ".$dbinfo->day ()." ".$dbinfo->hour ()." ".$dbinfo->minute ()."<br/>";
echo "Logged in? ".(($dbinfo->logged_in ())? "True" : "False")."<br/>";
echo "User? ".$dbinfo->user ()."<br/>";
echo "Admin? ".(($dbinfo->is_admin ())? "True" : "False")."<br/>";

echo current_script ();

echo_footer ();
$dbinfo->close ();

?>
