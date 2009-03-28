<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
$dbinfo->init ();
if (!$dbinfo->connect ())
	die ("Couldn't connect to database.");
$dbinfo->logout ();
?>