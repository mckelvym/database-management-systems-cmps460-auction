<?php
include_once ("common.php");

$dbinfo = new dbinfo_t();
echo_header($dbinfo);

// If the user is not logged in, redirect him to the index page.
if (!$dbinfo->logged_in())
	redirect("index.php");
// Checking the mode
$mode = get("mode");

$user_name = $dbinfo->username();
//If mode is empty, the user is current user
if ($mode == "") {
		
	$user_name = $dbinfo->username();
	
}// Viewing someother's profile
else if($mode == "view")
{
	$user_name = get("username");
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
echo $table->td_span("<img src=\"images/" . $dbinfo->get_picture($user_name) . "\" WIDTH=100 HEIGHT=100> ");
echo $table->tr_end();
//Displaying the user picture
echo $table->tr_begin();
echo $table->td_span("<font size='2' face='Verdana''>" . $dbinfo->get_realname($user_name));
echo $table->tr_end();
//Displaying the user description
echo $table->tr_begin();
echo $table->td_span("<font size='2' face='Verdana''>About me:" . $dbinfo->get_userdesc($user_name));
echo $table->tr_end();
echo $table->table_end();
?>
</td>
<td width="50%" valign="top">
<?php
if ($mode == "") {
	
	cout ("Welcome to your profile page.");
	
}// Viewing someother's profile
else
{
	cout ("Welcome to $user_name's profile page.");
}

cout ("");

#Query to check if the user is a seller and  has any feeback from buyer
$result_seller = $dbinfo->query ("select buyer,seller,buyerfeedbackforseller_rating,buyerfeedbackforseller_description from item_listing where seller ='$user_name' and buyerfeedbackforseller_rating!=-1 and buyerfeedbackforseller_description!='' and buyerfeedbackforseller_rating!=-1 order by end_day desc,end_hour desc,end_minute desc");
#Query to check if the user is a buyer  has any feeback from seller
$result_buyer = $dbinfo->query("select buyer, seller, sellerfeedbackforbuyer_description from item_listing where buyer = '$user_name' and sellerfeedbackforbuyer_description!=''");
if(mysql_num_rows($result_seller) == 0 && mysql_num_rows($result_buyer) == 0)
{
	echo div ("No feedback found!", "feedback");
}
if(mysql_num_rows($result_seller) != 0){
	cout ("Feedback for items sold :");
	while (list ($buyer, $seller, $buyerfeedbackforseller_rating, $buyerfeedbackforseller_description) = mysql_fetch_row($result_seller)) {
			
				
			if ($seller == $user_name) {
				$winner_realname = href ("profile.php?mode=view&username=$buyer", $dbinfo->get_realname ($buyer));
				echo div ("$winner_realname has left the following feedback \"$buyerfeedbackforseller_description\"", "feedback");

			}

		}	
}


if(mysql_num_rows($result_buyer)!=0)
{
	cout ("Feedback for items bought :");
	while (list ($buyer, $seller, $sellerfeedbackforbuyer_description) = mysql_fetch_row($result_buyer)) {
			
				
			if ($buyer == $user_name) {
				$seller_realname = href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller));
				echo div ("$seller_realname has left the following feedback \"$sellerfeedbackforbuyer_description\"", "feedback");

			}

		}	
}


?>
</td>
</tr>
</table>
<?php

echo_footer($dbinfo);
?>
