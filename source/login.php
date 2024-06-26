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

// User login form page






include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

if ($dbinfo->logged_in ())
	redirect ("index.php");

// display login form
$info = post ("username");
$mode = get ("login");
if (empty ($mode))
{
	$msg = get ("message");
	if (!empty ($msg))
		cout ($msg);
	echo h3 ("Login");
	$table = new table_common_t ();
	$table->init ("tbl_std");
	echo $table->table_begin ();
	echo $table->table_head_begin ();
	echo $table->tr ($table->td_span ("Login with your iBay ID", "", 2));
	echo $table->table_head_end ();
	echo $table->table_body_begin ();
	echo form_begin ("login.php?login=y", "post");
	echo $table->tr ($table->td ("Username").
			 $table->td (text_input_s ("username", "", 20, 50)));
	echo $table->tr ($table->td ("Password").
			 $table->td (password_input_s ("password", "", 20, 50)));
	echo $table->tr ($table->td_span (submit_input ("Login"), "", 2, "center"));
	echo form_end ();
	echo $table->table_body_end ();
	echo $table->table_end ();
}
// process login
else
{
	$user = fix_quotes (post ("username"));
	$pass = fix_quotes (post ("password"));
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