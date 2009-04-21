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

// Shows the user the available pictures for a profile






include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

cout ("The following are the available profile pictures:");

cout (local_img ("default_profile.jpg"));
cout ("default_profile.jpg");
cout ("");
for ($i = 1; $i <= 7; $i++) // assume 7 pictures are available
{
	cout (local_img ("profile$i.jpg"));
	cout ("profile$i.jpg");
	cout ("");
}

echo_footer ($dbinfo);
?>
