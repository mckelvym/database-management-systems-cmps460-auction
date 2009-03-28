<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$mode = get ("mode");

if ($dbinfo->logged_in ())
{
	if ($dbinfo->is_admin ())
	{
		echo "Soon, you'll see the admin registration panel.";
	}
	else
	{
		echo "Soon, you'll see your registration info.";
	}
}
else
{
	echo "Don't worry, soon you'll be able to register.";
}
echo "MODE = '$mode'";

echo_footer ($dbinfo);

?>
