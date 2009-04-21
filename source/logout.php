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

// Logout processing page






include_once ("common.php");

$dbinfo = new dbinfo_t ();
$dbinfo->init ();
if (!$dbinfo->connect ())
	die ("Couldn't connect to database.");
$dbinfo->logout (); // saves activity and destroys session
?>