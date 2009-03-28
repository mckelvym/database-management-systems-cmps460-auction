<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

if ($dbinfo->logged_in ())
{
	cout ("Welcome to the home page. There will be much more fun to come!");
	cout ("You'll get to see things like notifications and activities and feedback - oh my!");
}
else
{
	$loginlink = href ("login.php", "Login");
	$reglink= href ("registration.php", "Register");
	echo <<<HEREDOC
Welcome to CMPS460 Group I's eBay-ish website "iBay"! You will need to $loginlink or $reglink to proceed further.
HEREDOC;
}

echo_footer ($dbinfo);

?>
