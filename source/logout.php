<?php
include_once ("common.php");

// Need to handle saving the user activity of logout

$dbinfo = new dbinfo_t ();
$dbinfo->init ();
$dbinfo->logout ();

redirect ("index.php");
?>