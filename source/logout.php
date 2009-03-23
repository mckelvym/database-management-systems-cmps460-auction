<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
$dbinfo->init ();
$dbinfo->logout ();

redirect ("index.php");
?>