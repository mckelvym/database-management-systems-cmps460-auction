<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

if ($dbinfo->logged_in ())
	redirect ("index.php");

// display login form
$info = post ("username");
if (!isset ($info))
{
	$msg = get ("message");
	if (!empty ($msg))
		cout ($msg);
	cout ("Take a test spin in an admin account! jim_admin foursixty");
	$table = new table_common_t ();
	$table->init ("tbl_std");
	echo $table->table_begin ();
	echo $table->table_head_begin ();
	echo $table->tr ($table->td_span ("Login with your iBay ID", "", 2));
	echo $table->tr_end ();
	echo $table->table_body_begin ();
	echo form_begin ("login.php", "post");
	echo $table->tr ($table->td ("Username").
			 $table->td (text_input ("username", "")));
	echo $table->tr ($table->td ("Password").
			 $table->td (password_input ("password", "")));
	echo $table->tr ($table->td_span (submit_input ("Login"), "", 2));
	echo form_end ();
	echo $table->table_body_end ();
	echo $table->table_end ();
}
// process login
else
{
	$user = post ("username");
	$pass = post ("password");
	if ($dbinfo->login ($user, $pass))
	{
		$dbinfo->close ();
		redirect ("index.php");
	}
	else
	{
		$dbinfo->close ();
		redirect ("login.php?message=Login%20Failed!");
	}
}

echo_footer ($dbinfo);

?>