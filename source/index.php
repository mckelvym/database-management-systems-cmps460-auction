<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
$dbinfo->init ();


echo_header ();

/* Uncomment this code once we have access to the UL DB
if (!$dbinfo->connect ())
{
	echo "Couldn't connect to database.";
	echo_footer ();
	return;
}
*/

$table = new table_common_t ();
$table->init ("tbl_std");
echo $table->table_begin ();
echo $table->table_head_begin ();
echo $table->tr_begin ();
echo $table->td_span ("Login", "", 2);
echo $table->tr_end ();
echo $table->table_body_begin ();
echo form_begin ("index.php", "post");
echo $table->tr ($table->td ("Username").
		 $table->td (text_input ("user", "")));
echo $table->tr ($table->td ("Password").
		 $table->td (password_input ("pass", "")));
echo $table->tr ($table->td_span (submit_input ("Login"), "", 2));
echo form_end ();
echo $table->table_body_end ();
echo $table->table_end ();

echo_footer ();

?>
