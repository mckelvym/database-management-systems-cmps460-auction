<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
$dbinfo->init ();

if ($dbinfo->logged_in ())
	redirect ("index.php");

echo_header ($dbinfo);

if (!$dbinfo->connect ())
{
	echo "Couldn't connect to database.";
	echo_footer ();
	return;
}

// display login form
$info = post ("username");
if (!isset ($info))
{
	echo get ("message");
	$table = new table_common_t ();
	$table->init ("tbl_std");
	echo $table->table_begin ();
	echo $table->table_head_begin ();
	echo $table->tr ($table->td_span ("Login", "", 2));
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

echo_footer ();
$dbinfo->close ();
?>