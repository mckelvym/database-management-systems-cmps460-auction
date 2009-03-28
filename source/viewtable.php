<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

$table = get ("name");
if ($dbinfo->logged_in () && $dbinfo->is_admin ())
{
	cout ("Soon, you'll see information from the database table '$table'.");
	if ($table == "user")
	{
		cout ("Nothing yet");
	}
	else if ($table == "user_activity")
	{
		cout ("Nothing yet");
	}
	else if ($table == "item_listing")
	{
		cout ("Nothing yet");
	}
	else if ($table == "bids_on")
	{
		cout ("Nothing yet");
	}
}
else if ($dbinfo->logged_in ())
{
	redirect ("index.php");
}
else
{
	redirect ("index.php");
}

echo_footer ($dbinfo);

?>
