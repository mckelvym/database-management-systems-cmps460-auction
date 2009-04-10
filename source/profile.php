<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);


// If the user is not logged in, redirect him to the index page.
if (!$dbinfo->logged_in ())
	redirect ("index.php");
// Checking the mode
$mode = get ("mode");
$user_name = $dbinfo->username();
if ($mode=="")
{
	//
}	

	
?>
<table border="1" width="99%" cellpadding="10">
<tr>

<td width="5%" valign="top">
<?php



$table = new table_common_t();
echo $table->table_begin();
//Displaying the user picture
echo $table->tr_begin();
echo $table->td_span("<img src=\"images/".$dbinfo->get_picture($user_name)."\" WIDTH=100 HEIGHT=100> ");
echo $table->tr_end();
//Displaying the user picture
echo $table->tr_begin();
echo $table->td_span("<font size='2' face='Verdana''>".$dbinfo->realname());
echo $table->tr_end();
//Displaying the user description
echo $table->tr_begin();
echo $table->td_span("<font size='2' face='Verdana''>About me:".$dbinfo->get_userdesc($user_name));
echo $table->tr_end();
echo $table->table_end();
?>
</td>
<td width="50%" valign="top">
<?php
$table = new table_common_t();
echo $table->table_begin();
echo $table->tr_begin();
echo $table->td("Welcome, ".$dbinfo->realname());
echo $table->tr_end();
echo $table->tr_begin();
echo $table->td("<br>");
echo $table->tr_end();
echo $table->tr_begin();


$result = $dbinfo->query ("select buyerfeedbackforseller_description from item_listing where seller ='$user_name'");
$emptyctr = 0; #Counter which flags number of empty string
#Check if the list contains empty string
while (list($buyerfeedbackforseller_description) = mysql_fetch_row ($result))
{
	if($buyerfeedbackforseller_description=="")
		$emptyctr++;
			
}
			
#If mysql_num_rows is 0,or No of empty strings = no of rows there are no feedbacks!
if ((mysql_num_rows ($result)==0)||(mysql_num_rows ($result)==$emptyctr))
{
	$table = new table_common_t ();
	$table->init ("tbl_std");
	echo $table->table_begin ();
	echo $table->table_head_begin ();
	echo $table->tr ($table->td_span ("No Feedbacks found!", "", 6));
	echo $table->table_head_end ();
	echo $table->table_body_begin ();
			
		    echo $table->table_body_end ();
	echo $table->table_end ();
}
else
{
			$result = $dbinfo->query ("select buyerfeedbackforseller_description from item_listing where seller ='$user_name'");
			$table = new table_common_t ();
			$table->init ("tbl_std");
			echo $table->table_begin ();
			echo $table->table_head_begin ();
			echo $table->tr ($table->td_span ("Feedback", "", 6));
			echo $table->tr ($table->td ("From Selling","",6).
					 $table->td ("From Buying"));
			echo $table->table_head_end ();
			echo $table->table_body_begin ();
			while (list($buyerfeedbackforseller_description) = mysql_fetch_row ($result))
			{
				if($buyerfeedbackforseller_description!="") #Removing empty string values from displayed
					echo $table->tr ($table->td ($buyerfeedbackforseller_description));
						
			}
		    echo $table->table_body_end ();
			echo $table->table_end ();
			mysql_free_result ($result);
}			
			


echo $table->tr_end();

echo $table->table_end();

?>
</td>

</tr>
</table>
<?php
echo_footer ($dbinfo);

?>
