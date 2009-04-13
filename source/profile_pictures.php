<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

cout ("The following are the available profile pictures:");

cout (local_img ("default_profile.jpg"));
cout ("default_profile.jpg");
cout ("");
for ($i = 1; $i <= 6; $i++)
{
	cout (local_img ("profile$i.jpg"));
	cout ("profile$i.jpg");
	cout ("");
}


echo_footer ($dbinfo);

?>
