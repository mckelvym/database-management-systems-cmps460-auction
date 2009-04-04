<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

if (!$dbinfo->logged_in ())
	redirect ("index.php");
?>
<table border="1" width="99%" cellpadding="10">
<tr>

<td width="15%" valign="top">
<?php

$user_name = $dbinfo->username();

$table = new table_common_t();
echo $table->table_begin();
echo $table->tr_begin();
echo $table->td_span("<font size='2' face='Verdana''>Name:".$dbinfo->realname());
echo $table->tr_end();
echo $table->tr_begin();
echo $table->td_span("<font size='2' face='Verdana''>Description:".$dbinfo->get_userdesc($user_name));
echo $table->tr_end();
echo $table->table_end();
?>
</td>
<td width="50%" valign="top">
// Notification goes here
</td>

</tr>
</table>
<?php
echo_footer ($dbinfo);

?>
