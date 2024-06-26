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

// Profile page for editing & viewing user (and your own) profile.






include_once ("common.php");

$dbinfo = new dbinfo_t();
echo_header($dbinfo);


// Javascript functions for image loading
echo "<script type=\"text/javascript\" src=\"profile.js\"></script>";	


$current_script = current_script ();
// If the user is not logged in, redirect him to the index page.
if (!$dbinfo->logged_in())
	redirect("index.php");


// Checking the mode
$mode = get("mode");

$user_name = $dbinfo->username();
$home_user = $user_name; //Saving the real user in home_user.

// simple validation
function verify_data ()
{
	global $dbinfo, $errors, $post_picture, $post_description;
	$errors = "";
	$post_picture = post("picture");
	$post_description = fix_quotes (post ("Description"));
	if(empty($post_description))
		$errors = $errors.li ("Description can't be empty");

	return $errors;
}

//If mode is empty, the user is current user
if ($mode == "")
{
	$user_name = $dbinfo->username ();
}

// Reading username from the get request
else if ($mode == "view" || $mode == "edit"|| $mode == "delete")
{
	$user_name = get("username");
}


// Default view
if ($mode == "" || $mode == "view" )
{
	echo_div ("scriptstatus");
	if($user_name != $home_user)
		echo href ("$current_script?", "View My Profile");
	else{
		echo href ("$current_script?mode=edit&username=$user_name", "Edit Profile");
		echo (" | ");
		echo href ("$current_script?mode=delete&username=$user_name", "Delete Profile");
	}
	end_div ();

	$table = new table_common_t();
	$table->init ("profile");

	echo $table->table_begin();
	echo $table->table_head_begin ();
	if($user_name == $home_user)
	{//You are visiting your own page
		echo $table->tr ($table->td ("Your Profile"));
	}
	else
	{
		echo $table->tr ($table->td ($dbinfo->get_realname ($user_name)."'s Profile"));
	}
	echo $table->table_head_end ();
	echo $table->table_body_begin ();
	//Displaying the user picture
	echo $table->tr ($table->td (local_img ($dbinfo->get_picture($user_name))).
			 $table->td ("About me: " . $dbinfo->get_userdesc($user_name)));
	//Displaying the user description
	echo $table->table_body_end ();
	echo $table->table_end();

	cout ("");
	if($user_name != $home_user)
		cout ("Feedback for ".$dbinfo->get_realname ($user_name).":");
	else
		cout ("Feedback for you:");

#Query to check if the user is a seller and  has any feeback from buyer
	$result_seller = $dbinfo->query ("select buyer,seller,buyerfeedbackforseller_rating,buyerfeedbackforseller_description from item_listing where seller ='$user_name' and buyerfeedbackforseller_rating!=-1 and buyerfeedbackforseller_description!='' and buyerfeedbackforseller_rating!=-1 order by end_day desc,end_hour desc,end_minute desc");
#Query to check if the user is a buyer  has any feeback from seller
	$result_buyer = $dbinfo->query("select buyer, seller, sellerfeedbackforbuyer_description from item_listing where buyer = '$user_name' and sellerfeedbackforbuyer_description!=''");
	if(mysql_num_rows($result_seller) == 0 && mysql_num_rows($result_buyer) == 0)
	{
		echo div ("No feedback found.", "feedback");
	}
	if(mysql_num_rows($result_seller) != 0)
	{
		cout ("");
		cout ("Feedback for items sold:");
		while (list ($buyer, $seller,
			     $buyerfeedbackforseller_rating,
			     $buyerfeedbackforseller_description)
		       = mysql_fetch_row($result_seller))
		{
			if ($seller == $user_name)
			{
				$winner_realname = href ("profile.php?mode=view&username=$buyer",
							 $dbinfo->get_realname ($buyer));
				echo div ("$winner_realname has left the following feedback \"$buyerfeedbackforseller_description\"", "feedback");
			}
		}
	}
	if(mysql_num_rows($result_buyer) != 0)
	{
		cout ("");
		cout ("Feedback for items bought:");
		while (list ($buyer, $seller,
			     $sellerfeedbackforbuyer_description)
		       = mysql_fetch_row($result_buyer))
		{
			if ($buyer == $user_name)
			{
				$seller_realname = href ("profile.php?mode=view&username=$seller",
							 $dbinfo->get_realname ($seller));
				echo div ("$seller_realname has left the following feedback \"$sellerfeedbackforbuyer_description\"", "feedback");
			}
		}
	}

	// Bid History
	cout ("");
	if($user_name != $home_user)
		cout ("Bid history for ".$dbinfo->get_realname ($user_name).":");
	else
		cout ("Bid history for you:");

	$result_bid =  $dbinfo->query ("select * from bids_on
where 	username = '$user_name'
order by bid_day desc, bid_hour desc, bid_minute desc");
	if(mysql_num_rows($result_bid) == 0)
	{
		echo div ("No bid history found.", "bidhistory");
	}
	else
	{
		while (list ($username, $item_title, $item_seller, $item_category,
			     $item_end_day, $item_end_hour, $item_end_minute,
			     $bid_day, $bid_hour, $bid_minute,
			     $bid_amount)
		       = mysql_fetch_row($result_bid))
		{
			$seller_realname = href ("profile.php?mode=view&username=$item_seller",
						 $dbinfo->get_realname ($item_seller));
			if ($username == $home_user)
				$bidder_realname = "You";
			else
			{
				$bidder_realname = href ("profile.php?mode=view&username=$username",
							 $dbinfo->get_realname ($username));
			}

			echo div (span (format_time ($bid_day, $bid_hour, $bid_minute), "time2").
				  "$bidder_realname  made a bid of \$$bid_amount on $seller_realname's auction : \"".href ("itemlisting.php?mode=view&title=$item_title&seller=$item_seller&category=$item_category&end_day=$item_end_day&end_hour=$item_end_hour&end_minute=$item_end_minute", $item_title)."\".", "bidhistory");
		}
	}
}
else if ($mode == "browse")
{
	echo h3 ("All Profiles");
	echo_div ("scriptstatus");
	echo href ("$current_script?", "View My Profile");
	end_div ();

	$curr_user = $dbinfo->username ();
	$result = $dbinfo->query ("select u.username,  realname,  day, hour, minute
from user u, user_activity ua
where 	u.username = ua.username
AND 	u.username != '$curr_user'
AND	ua.activity = 'Registered'
order by username");

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
		while (list($username,  $name,  $day, $hour, $min)
		       = mysql_fetch_row ($result))
		{
			echo $table->tr ($table->td ($username).
					 $table->td ($name).
					 $table->td (format_time ($day, $hour, $min)).
					 $table->td (href ("profile.php?mode=view&username=$username",
							   "Show Profile")));
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
	if($user_name != $home_user)
	{
		//If the user tries to edit the profile of other users, deny access.
		cout ("Access Denied!");
	}
	else
	{
		if ($dbinfo->logged_in ())
			echo form_begin ("$current_script?mode=save", "post");

		//Retrieving inforamtion about the user from database.
		$result = $dbinfo->query ("select username, description , picture from user where username = '$user_name'");
		if ($result)
		{
			list($username, $description ,$picture)
				= mysql_fetch_row ($result);
		}

		$table = new table_common_t ();
		$table->init ("profile_edit");

		echo $table->table_begin ();
		echo $table->table_head_begin ();
		if ($dbinfo->logged_in ())
			echo $table->tr ($table->td_span ("Update your iBay profile", "", 2));
		echo $table->table_head_end ();
		echo $table->table_body_begin ();
		if ($dbinfo->logged_in ())
			echo form_begin ("$current_script?mode=save", "post");
		$options = "";
		//Checking if the user has default picture, display default picture details.
		if( $picture == "default_profile.jpg") // Users has default picture
		{
			$options = $options.option ("default_profile.jpg", "Default", $usrpicture);
			for ($i = 1; $i <= 7; $i++)
			{
				$options = $options.option ("profile$i.jpg", "Picture $i", $usrpicture);
			}
		}
		else
		{	//Display the picture details in database first

			for ($k = 1; $k <= 7; $k++)
			{
				if($picture=="profile$k.jpg")
					break;

			}
			$options = $options.option ("profile$k.jpg", "Picture $k", $usrpicture); //Showing the existing picture from database
			$options = $options.option ("default_profile.jpg", "Default", $usrpicture);//Including the default picture
			//Displaying other picture information, except the already exisisting picture
			for ($i = 1; $i <= 7; $i++)
			{
				if("Picture $k" != "Picture $i") //Everything except the previous selected picture
					$options = $options.option ("profile$i.jpg", "Picture $i", $usrpicture);

			}
		}
		echo $table->tr ($table->td ("Profile Picture").
				 $table->td (select_dyn ("picture", $options, "image_swap()").
					     hreft ("profile_pictures.php", "?", "_blank")."<br/>".
					     local_img ($picture)));

		if($description=="N/A") // Users has no description
		{
					//If the Desc is N/A show a blank text box for input
					echo $table->tr ($table->td ("About Me").
					$table->td (text_input_s ("Description", $usrDesc, 70, 100)));
		}
		else
		{
			//Display already existing description
			echo $table->tr ($table->td ("About Me").
					 $table->td (text_input_s ("Description", $description, 70, 100)));
		}
		echo $table->tr ($table->td_span (submit_input ("Save"), "", 2, "center"));
		echo form_end ();
		echo $table->table_body_end ();
		echo $table->table_end ();
	}
}
else if ($mode == "save") //If the mode is save
{
	verify_data();// Verifying the data
	//If errors are detected
	if (!empty ($errors))
	{
		echo "Errors were detected. Please correct before continuing:<br/>";
		echo ul ($errors); // Displaying errors
		echo href ("$current_script?mode=edit&username=$user_name", "Back to Edit Profile."); //Linking back to edit page
	}
	else
	{	//If no errors are found, proceed saving data
		$post_picture = post("picture"); //Getting picture information
		$post_description = fix_quotes (post ("Description")); //Getting the description
		// Update the database
		$dbinfo->query ("update user set picture = '$post_picture', description = '$post_description' where username = '$user_name'");
		//Saving the acitivity
		$dbinfo->save_activity ("You updated your profile information.");
		cout ("Update successful.");
		echo href ("$current_script?mode=view&username=$user_name", "Click to refresh");//linking to profile page
	}

}
//If the mode is delete, clear profile information
else if($mode = "delete")
{
		//check to see if the user is trying to delete someone else profile
		if($user_name != $home_user) // If the users are different, Deny access
		{
			cout ("Access denied");
		}
		else{
			//Users are same.
			//Clearing the user from database.
			$result = $dbinfo->query (" update user set Description = 'N/A',picture = 'default_profile.jpg' where username = '$user_name'");
			$dbinfo->save_activity ("You deleted your profile information."); //Saving activity
			cout ("You have cleared your profile information.");
			echo href ("$current_script?mode=view&username=$user_name", "Click to refresh"); //Proceed to view profile page
		}

}

echo_footer($dbinfo);
?>
