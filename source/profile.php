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

$table = new table_common_t();
echo $table->table_begin();
echo $table->tr_begin();
if ($mode == "") {
	//For current users mode, display welcome message
	echo $table->td("Welcome, " . $dbinfo->realname());
}
else if($mode == "view"){
	
	#If the Realname is empty, do not display anything
	if($dbinfo->get_realname($user_name)!=""){ 
		echo $table->td($dbinfo->get_realname($user_name)."' Profile");
	}
	else{ 
		echo $table->td("Unknown Profile");
	}
	

}



echo $table->tr_end();
echo $table->tr_begin();
echo $table->td("<br>");
echo $table->tr_end();
echo $table->tr_begin();

#Query to check if the user is a seller and  has any feeback from buyer
$result_seller = $dbinfo->query("select buyer,seller,buyerfeedbackforseller_rating,buyerfeedbackforseller_description from item_listing where seller ='$user_name' and buyerfeedbackforseller_rating!=-1 and buyerfeedbackforseller_description!=''");
#Query to check if the user is a buyer  has any feeback from seller
$result_buyer = $dbinfo->query("select buyer, seller, sellerfeedbackforbuyer_description from item_listing where buyer = '$user_name' and sellerfeedbackforbuyer_description!=''");

#If mysql_num_rows is 0, there will be  no feedbacks!
if ((mysql_num_rows($result_seller) == 0) and (mysql_num_rows($result_buyer) == 0)) {
	$table = new table_common_t();
	$table->init("tbl_std");
	echo $table->table_begin();
	echo $table->table_head_begin();
	echo $table->tr($table->td_span("No Feedbacks found!", "", 6));
	echo $table->table_head_end();
	echo $table->table_body_begin();
	echo $table->table_body_end();
	echo $table->table_end();
} else #Feedback exists
	{
	$table = new table_common_t();
	$table->init("tbl_std");
	echo $table->table_begin();
	echo $table->table_head_begin();
	echo $table->tr_begin();
	echo $table->td("Feedback");
	echo $table->tr_end();
	echo $table->table_end();
?>
			<br>
			<?php

	if (mysql_num_rows($result_seller) != 0) { #check if any Buyer's Feedback exists

		$table = new table_common_t();
		$table->init("tbl_std");
		echo $table->table_begin();
		echo $table->table_head_begin();
		echo $table->tr_begin();

		echo $table->td("Buyer's Feedback for you");

		echo $table->tr_end();

		echo $table->table_head_end();

		echo $table->table_body_begin();

		$table = new table_common_t();
		$table->init("tbl_std");
		echo $table->table_begin();
		echo $table->table_head_begin();

		echo $table->tr($table->td("Buyer") . $table->td("Rating") . $table->td("Feedback"));

		echo $table->table_head_end();
		echo $table->table_body_begin();
		$result = $dbinfo->query("select buyer,seller,buyerfeedbackforseller_rating,buyerfeedbackforseller_description from item_listing where seller ='$user_name' and buyerfeedbackforseller_rating!=-1 and buyerfeedbackforseller_description!='' order by end_day desc,end_hour desc,end_minute desc");
		#Listing the buyer,buyerfeedbackforseller_rating & buyerfeedbackforseller_description
		while (list ($buyer, $seller, $buyerfeedbackforseller_rating, $buyerfeedbackforseller_description) = mysql_fetch_row($result)) {

			if ($seller == $user_name) {

				echo $table->tr($table->td($buyer) . $table->td($buyerfeedbackforseller_rating) . $table->td($buyerfeedbackforseller_description));

			}

		}

		echo $table->table_body_end();
		echo $table->table_end();
		mysql_free_result($result);
	}
?>
<br>					
<?php

	if (mysql_num_rows($result_buyer) != 0) { #check if any seller's Feedback exists

		$table = new table_common_t();
		$table->init("tbl_std");
		echo $table->table_begin();
		echo $table->table_head_begin();
		echo $table->tr_begin();

		echo $table->td("Seller's Feedback for you");

		echo $table->tr_end();
		echo $table->table_head_end();
		echo $table->table_body_begin();

		$table = new table_common_t();
		$table->init("tbl_std");
		echo $table->table_begin();
		echo $table->table_head_begin();

		echo $table->tr($table->td("Seller") . $table->td("Feedback"));

		echo $table->table_head_end();
		echo $table->table_body_begin();
		$result = $dbinfo->query("select buyer, seller, sellerfeedbackforbuyer_description from item_listing where buyer = '$user_name' and sellerfeedbackforbuyer_description!='' order by end_day desc,end_hour desc,end_minute desc");
		#Listing the buyer,seller,sellerfeedbackforbuyer_description
		while (list ($buyer, $seller, $sellerfeedbackforbuyer_description) = mysql_fetch_row($result)) {

			if ($buyer == $user_name) {

				echo $table->tr($table->td($seller) . $table->td($sellerfeedbackforbuyer_description));
			}
		}

		echo $table->table_body_end();
		echo $table->table_end();

		echo $table->table_body_end();
		echo $table->table_end();
	}

}

echo $table->tr_end();
echo $table->table_end();

?>
</td>
</tr>
</table>
<?php

echo_footer($dbinfo);
?>
