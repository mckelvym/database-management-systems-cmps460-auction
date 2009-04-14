<?php
include_once ("common.php");

$dbinfo = new dbinfo_t();
echo_header($dbinfo);
$current_script = current_script ();
// If the user is not logged in, redirect him to the index page.
if (!$dbinfo->logged_in())
	redirect("index.php");
// Checking the mode
$mode = get("mode");

$user_name = $dbinfo->username();
$home_user = $user_name;

function verify_data ()
{
	global $dbinfo, $errors, $post_picture, $post_description;
		$errors = "";
		$post_picture = post("picture");
		$post_description = post ("Description");
		if(empty($post_description))
			$errors = $errors.li ("Description can't be empty");
		
	return $errors;
}

//If mode is empty, the user is current user
if ($mode == "") {
		
	$user_name = $dbinfo->username();
	
}// Viewing someother's profile
else if($mode == "view"||$mode == "edit")
{
	$user_name = get("username");
}
if($mode == ""||$mode == "view" ){ 
	
	echo_div ("scriptstatus");
	if($user_name!=$home_user)
		echo href ("$current_script?", "View My Profile");
	else
		echo href ("$current_script?mode=edit&username=$user_name", "Edit Profile");
	
	end_div ();
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
	else if($mode == "view")
	{
		if($user_name == $home_user){//You are visinting your own page
				cout ("Welcome to your profile page.");
		}else
		cout ("Welcome to $user_name's profile page.");
	}
	
	
	
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
		cout ("");
		cout ("Feedback for items bought :");
		while (list ($buyer, $seller, $sellerfeedbackforbuyer_description) = mysql_fetch_row($result_buyer)) {
				
					
				if ($buyer == $user_name) {
					$seller_realname = href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller));
					echo div ("$seller_realname has left the following feedback \"$sellerfeedbackforbuyer_description\"", "feedback");
	
				}
	
			}	
	}
	cout ("");
	
	$result_bid =  $dbinfo->query (" select username,item_seller, item_title,item_category ,item_end_day, item_end_hour, item_end_minute, bid_amount from bids_on where username = '$user_name' order by bid_amount desc");
	if(mysql_num_rows($result_bid)==0)
	{
		echo div ("No bid history found!", "bidhistory");
		
	}
	else{ 
		cout ("Bid history:");
		while (list ($username, $item_seller, $item_title,$item_category, $item_end_day, $item_end_hour,$item_end_minute,$bid_amount) = mysql_fetch_row($result_bid)) {
					
					$seller_realname = href ("profile.php?mode=view&username=$item_seller", $dbinfo->get_realname ($item_seller));
					
					if($username == $home_user)			
						$bidder_realname = "You";
					else{
					$bidder_realname = href ("profile.php?mode=view&username=$username", $dbinfo->get_realname ($username));
					
					}
					
					echo div (span (format_time ($bid_day, $bid_hour, $bid_minute), "time").
							  "$bidder_realname  made a bid of \$$bid_amount on $seller_realname's auction : \"".href ("itemlisting.php?mode=view&title=$item_title&seller=$item_seller&category=$item_category&end_day=$item_end_day&end_hour=$item_end_hour&end_minute=$item_end_minute", $item_title)."\".", "bidhistory");
		
				}	
	}
	?>
	</td>
	</tr>
	</table>
	<?php
}
else if ($mode == "browse")
{
	echo_div ("scriptstatus");
	echo href ("$current_script?", "View My Profile");
	end_div ();
		$curr_user = $dbinfo->username ();
		$result = $dbinfo->query ("select u.username,  realname,  day, hour, minute
from user u, user_activity ua
where u.username = ua.username AND
u.username != '$curr_user' AND
ua.activity = 'Registered' order by username");
		if ($result)
		{
			$table = new table_common_t ();
			$table->init ("tbl_std");
			echo $table->table_begin ();
			echo $table->table_head_begin ();
			echo $table->tr ($table->td_span ("iBay Users", "", 6));
			echo $table->tr ($table->td ("Username").
					 $table->td ("Realname").				 
					 $table->td ("Registration Time").
					 $table->td ("Profile"));
			echo $table->table_head_end ();
			echo $table->table_body_begin ();
			while (list($username,  $name,  $day, $hour, $min) = mysql_fetch_row ($result))
			{
				echo $table->tr ($table->td ($username).
						 $table->td ($name).
						 $table->td (format_time ($day, $hour, $min)).
						 $table->td (href ("profile.php?mode=view&username=$username", "Show Profile")));
			}
			echo $table->table_body_end ();
			echo $table->table_end ();
			mysql_free_result ($result);
		}
		else if ($dbinfo->debug ())
		{
			mysql_free_result ($result);
			die (mysql_error ());
		}
		else
		{
			mysql_free_result ($result);
			echo "Couldn't get user information.";
			
		}
}
else if ($mode == "edit")
{
	//If mode is edit, user is allowed to change his picture and profile description.
	if($user_name!=$home_user){
		//If the user tries to edit the profile of other users, deny access.
?>
		<div style="text-align: center;">> Access Denied <</div>
<?php
		
	}
	else{ 
		
		if ($dbinfo->logged_in ())
			echo form_begin ("$current_script?mode=save", "post");		
		
		//Retrieving inforamtion about the user from database.
		$result = $dbinfo->query ("select username, description , picture from user where username = '$user_name'");
		if ($result)
		{
			list($username, $description ,$picture) = mysql_fetch_row ($result);
			
		}
		
		
		$table = new table_common_t ();
		$table->init ("tbl_std");
	
		echo $table->table_begin ();
		echo $table->table_head_begin ();
		if ($dbinfo->logged_in ())		
			echo $table->tr ($table->td_span ("Update your iBay profile", "", 2));
	
		echo $table->tr_end ();
		echo $table->table_head_end ();
		echo $table->table_body_begin ();
		if ($dbinfo->logged_in ())
			echo form_begin ("$current_script?mode=save", "post");
			$options = "";
		//Checking if the user has default picture, display default picture details.
		if($picture=="default_profile.jpg") // Users has default picture
		{ 
			$options = $options.option ("default_profile.jpg", "Default", $usrpicture);
			for ($i = 1; $i <= 7; $i++)
			{
				$options = $options.option ("profile$i.jpg", "Picture $i", $usrpicture);			
			}
		}			
		else
		{//Display the picture details in database first
//			$options = $options.option ($picture, $picture, $usrpicture);
				
				for ($k = 1; $k <= 7; $k++)
				{
					if($picture=="profile$k.jpg")
						break;
					
				}
			$options = $options.option ("profile$k.jpg", "Picture $k", $usrpicture);	
			$options = $options.option ("default_profile.jpg", "Default", $usrpicture);
			
				for ($i = 1; $i <= 7; $i++)
				{
					if("Picture $k" != "Picture $i")
						$options = $options.option ("profile$i.jpg", "Picture $i", $usrpicture);
					
				}
		}			
		echo $table->tr ($table->td ("Profile Picture").
				 $table->td (select ("picture", $options).
					     href ("profile_pictures.php", "?")));
				     
		if($description=="N/A") // Users has no description
		{
			
			echo $table->tr ($table->td ("About Me").
			 $table->td (text_input_s ("Description", $usrDesc, 70, 100)));
		}
		else
		{	
			
			echo $table->tr ($table->td ("About Me").
			 $table->td (text_input_s ("Description", $description, 70, 100)));
		}
		echo $table->tr ($table->td_span (submit_input ("Save"), "", 2, "center"));		
		echo form_end ();
	echo $table->table_body_end ();
	echo $table->table_end ();     
		
	}
}
else if ($mode == "save")
{
	verify_data();
	if (!empty ($errors))
	{
		echo "Errors were detected. Please correct before continuing:<br/>";
		echo ul ($errors);
		echo href ("$current_script?mode=edit&username=$user_name", "Back to Edit Profile.");
	}
	else
	{
		$post_picture = post("picture");
		$post_description = post ("Description");		
		// Update the database
		$dbinfo->query ("update user set picture = '$post_picture', description = '$post_description' where username = '$user_name'");
		
		$dbinfo->save_activity ("You updated your registration information.");
		cout ("Update successful.");
			echo href ("$current_script?mode=view&username=$user_name", "Click to refresh");
	}
	
}

echo_footer($dbinfo);
?>
