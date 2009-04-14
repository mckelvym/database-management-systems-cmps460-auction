<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

echo <<<HEREDOC
<script type="text/javascript">
function reset_inputs (thisform)
{
	// reset inputs
	// http://webcheatsheet.com/javascript/form_validation.php
	for each (elem in thisform.elements)
	{
		if (elem.type == "text" || elem.type == "select-one")
		{
			elem.style.background = '';
		}
	}
}

function validate_required (field)
{
	// http://www.w3schools.com/jS/js_form_validation.asp
	// http://webcheatsheet.com/javascript/form_validation.php
	with (field)
	{
		if (value == null || value == "" || value == "-")
		{
			field.style.background = 'Yellow';
			return false;
		}
		else
		{
			return true;
		}
	}
}

function field_set (fieldvar, field)
{
	if (fieldvar == null)
		return field;
	else
		return fieldvar;
}

function append_with_newline (orig, append_text)
{
	if (orig != null)
		return orig + '\\n' + append_text;
	return append_text;
}

function validate_email (field)
{
	// http://www.w3schools.com/jS/js_form_validation.asp
	// http://webcheatsheet.com/javascript/form_validation.php

	var email_filter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
	var illegal_chars= /[\(\)\<\>\,\;\:\\\"\[\]]/;
	var value = trim (field.value);

	if (value == null
		|| value == ""
		|| !email_filter.test (value)
		|| value.match (illegal_chars))
	{
		field.style.background = 'Yellow';
		return false;
	}
	return true;
}

function trim (string)
{
	// regex to replace one or more spaces at beginning of string
	// or one or more spaces at end of string, with empty string
	return string.replace (/^\s+|\s+$/, '');
}

function validate_int (field, length)
{
	with (field)
	{
		if (value.length < length || value * 1 == 0)
		{
			field.style.background = 'Yellow';
			return false;
		}
		else
		{
			return true;
		}
	}
}

function validate_form (thisform)
{
	var field_of_focus;
	var alert_message;

	reset_inputs (thisform);

	with (thisform)
	{
		if (validate_required (username) == false)
		{
			field_of_focus = field_set (field_of_focus, username);
			alert_message = append_with_newline (alert_message, "Username required.");
		}
		if (validate_required (password) == false)
		{
			field_of_focus = field_set (field_of_focus, password);
			alert_message = append_with_newline (alert_message, "Password required.");
		}
		if (validate_required (password2) == false)
		{
			field_of_focus = field_set (field_of_focus, password2);
			alert_message = append_with_newline (alert_message, "Password confirmation required.");
		}
		if (password.value != password2.value)
		{
			field_of_focus = field_set (field_of_focus, password);
			alert_message = append_with_newline (alert_message, "Passwords must match.");
		}
		if (validate_required (realname) == false)
		{
			field_of_focus = field_set (field_of_focus, realname);
			alert_message = append_with_newline (alert_message, "Real name required.");
		}
		if (validate_required (birth_date) == false)
		{
			field_of_focus = field_set (field_of_focus, birth_date);
			alert_message = append_with_newline (alert_message, "Birth date required.");
		}
		else if ((birth_date.value.length >= 6) == false)
		{
			field_of_focus = field_set (field_of_focus, birth_date);
			alert_message = append_with_newline (alert_message, "Birth date too short.");
		}
		if (validate_required (shipping_street) == false)
		{
			field_of_focus = field_set (field_of_focus, shipping_street);
			alert_message = append_with_newline (alert_message, "Street required.");
		}
		if (validate_required (shipping_city) == false)
		{
			field_of_focus = field_set (field_of_focus, shipping_city);
			alert_message = append_with_newline (alert_message, "City required.");
		}
		if (validate_required (shipping_zip) == false)
		{
			field_of_focus = field_set (field_of_focus, shipping_zip);
			alert_message = append_with_newline (alert_message, "Postal/zip code required.");
		}
		else if (validate_int (shipping_zip, 5) == false) // this test works only for US codes
		{
			field_of_focus = field_set (field_of_focus, shipping_zip);
			alert_message = append_with_newline (alert_message, "Postal/zip code is not valid.");
		}
		if (validate_required (phone) == false)
		{
			field_of_focus = field_set (field_of_focus, phone);
			alert_message = append_with_newline (alert_message, "Phone number required.");
		}
		else if ((phone.value.length >= 10) == false)
		{
			field_of_focus = field_set (field_of_focus, phone);
			alert_message = append_with_newline (alert_message, "Phone number required.");
		}
		if (validate_required (email) == false)
		{
			field_of_focus = field_set (field_of_focus, email);
			alert_message = append_with_newline (alert_message, "E-mail required.");
		}
		else if (validate_email (email) == false)
		{
			field_of_focus = field_set (field_of_focus, email);
			alert_message = append_with_newline (alert_message, "E-mail address not valid.");
		}
		if (validate_required (card_number) == false)
		{
			field_of_focus = field_set (field_of_focus, card_number);
			alert_message = append_with_newline (alert_message, "Credit card number required.");
		}
		else if (validate_int (card_number, 12) == false)
		{
			field_of_focus = field_set (field_of_focus, card_number);
			alert_message = append_with_newline (alert_message, "Credit card number is too short.");
		}

		if (alert_message != null)
		{
			alert (alert_message);
			field_of_focus.focus();
			return false;
		}
	}
}

/* onload = function() */
/* { */
/* } */

</script>
HEREDOC;


$mode = get ("mode");
$current_script = current_script ();

$get_username = get ("username");
$get_msg = get ("msg");

if (!empty ($get_msg))
	echo "$msg";

// Main registration form
function registration_form ($user = "")
{
	global $dbinfo;
	global $current_script;

	// If editing registration, fetch the current information from the database
	if (!empty ($user) && $dbinfo->user_exists ($user))
	{
		$result = $dbinfo->query ("select * from user where username = '$user'");
		if ($result)
		{
			list($username, $passwd, $is_admin, $name, $dob, $street,
			     $city, $state, $zip, $phone, $email, $cc, $ccn, $ccx,
			     $picture, $desc) = mysql_fetch_row ($result);
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
			return;
		}
	}
	else if (!empty ($username))
	{
		echo "You've reached this page in error.";
		return;
	}

	// Start writing table to page
	echo h3 ("Accounts and Registration");
	$table = new table_common_t ();
	$table->init ("tbl_std");

	echo $table->table_begin ();
	echo $table->table_head_begin ();
	if (!$dbinfo->logged_in ())
		echo $table->tr ($table->td_span ("Register for an iBay account", "", 2));
	else if ($dbinfo->logged_in () && $user != $dbinfo->username ())
		echo $table->tr ($table->td_span ("Update (not) your iBay account", "", 2));
	else
		echo $table->tr ($table->td_span ("Update your iBay account", "", 2));
	echo $table->table_head_end ();
	echo $table->table_body_begin ();

	// Different modes depending on if user is logged in or not
	if ($dbinfo->logged_in ())
		echo form_begin_n ("$current_script?mode=save", "post", "registration_form", "return validate_form (this)");
	else
		echo form_begin_n ("$current_script?mode=savenew", "post", "registration_form", "return validate_form (this)");

	// If editing, then make username field read only
	if (empty ($user))
		echo $table->tr ($table->td ("Desired Username").
				 $table->td (text_input_s ("username", $username, 30, 50).
					     alert ("50 characters or less. Have fun.", "?")));
	else
		echo $table->tr ($table->td ("Username<br/>".href ("$current_script?mode=delete&username=$username", "Delete Account")).
				 $table->td (text_input_sr ("username", $username, 30, 50)));

	// Passwords must match
	echo $table->tr ($table->td ("Desired Password").
			 $table->td (password_input_s ("password", $passwd, 30, 50).
				     alert ("50 characters or less, must match. Make it super secret.", "?")));
	echo $table->tr ($table->td ("Repeat Password").
			 $table->td (password_input_s ("password2", $passwd, 30, 50).
				     alert ("You thought there would be something helpful here. Just type the same thing as in the above box.", "?")));

	// Only allow editing of this if the current user is already an admin
	if ($dbinfo->is_admin ())
	{
		$options = "";
		$options = $options.option ("0", "No", $is_admin);
		$options = $options.option ("1", "Yes", $is_admin);
		echo $table->tr ($table->td ("Admin").
				 $table->td (select ("is_admin", $options).
					     alert ("Give administrator priv.", "?")));
	}
	echo $table->tr ($table->td ("Real Name").
			 $table->td (text_input_s ("realname", $name, 30, 100).
				     alert ("100 characters or less. e.g. John Smith", "?")));
	echo $table->tr ($table->td ("Birth Date").
			 $table->td (text_input_s ("birth_date", $dob, 10, 10).
				     alert ("Up to 10 characters, at least 6. e.g. 1980-10-17", "?")));
	echo $table->tr ($table->td ("Shipping Street").
			 $table->td (text_input_s ("shipping_street", $street, 30, 100).
				     alert ("100 characters or less. e.g. 56 Dodo Ln", "?")));
	echo $table->tr ($table->td ("Shipping City").
			 $table->td (text_input_s ("shipping_city", $city, 30, 50).
				     alert ("50 characters or less. e.g. Eunice", "?")));
	$options = "";
	$options = $options.option ("Alabama", "Alabama", $state);
	$options = $options.option ("Alaska", "Alaska", $state);
	$options = $options.option ("American Samoa", "American Samoa", $state);
	$options = $options.option ("Arizona", "Arizona", $state);
	$options = $options.option ("Arkansas", "Arkansas", $state);
	$options = $options.option ("California", "California", $state);
	$options = $options.option ("Colorado", "Colorado", $state);
	$options = $options.option ("Connecticut", "Connecticut", $state);
	$options = $options.option ("Delaware", "Delaware", $state);
	$options = $options.option ("District of Columbia", "District of Columbia", $state);
	$options = $options.option ("Florida", "Florida", $state);
	$options = $options.option ("Georgia", "Georgia", $state);
	$options = $options.option ("Guam", "Guam", $state);
	$options = $options.option ("Hawaii", "Hawaii", $state);
	$options = $options.option ("Idaho", "Idaho", $state);
	$options = $options.option ("Illinois", "Illinois", $state);
	$options = $options.option ("Indiana", "Indiana", $state);
	$options = $options.option ("Iowa", "Iowa", $state);
	$options = $options.option ("Kansas", "Kansas", $state);
	$options = $options.option ("Kentucky", "Kentucky", $state);
	$options = $options.option ("Louisiana", "Louisiana", $state);
	$options = $options.option ("Maine", "Maine", $state);
	$options = $options.option ("Maryland", "Maryland", $state);
	$options = $options.option ("Massachusetts", "Massachusetts", $state);
	$options = $options.option ("Michigan", "Michigan", $state);
	$options = $options.option ("Minnesota", "Minnesota", $state);
	$options = $options.option ("Mississippi", "Mississippi", $state);
	$options = $options.option ("Missouri", "Missouri", $state);
	$options = $options.option ("Montana", "Montana", $state);
	$options = $options.option ("Nebraska", "Nebraska", $state);
	$options = $options.option ("Nevada", "Nevada", $state);
	$options = $options.option ("New Hampshire", "New Hampshire", $state);
	$options = $options.option ("New Jersey", "New Jersey", $state);
	$options = $options.option ("New Mexico", "New Mexico", $state);
	$options = $options.option ("New York", "New York", $state);
	$options = $options.option ("North Carolina", "North Carolina", $state);
	$options = $options.option ("North Dakota", "North Dakota", $state);
	$options = $options.option ("Northern Marianas Islands", "Northern Marianas Islands", $state);
	$options = $options.option ("Ohio", "Ohio", $state);
	$options = $options.option ("Oklahoma", "Oklahoma", $state);
	$options = $options.option ("Oregon", "Oregon", $state);
	$options = $options.option ("Pennsylvania", "Pennsylvania", $state);
	$options = $options.option ("Puerto Rico", "Puerto Rico", $state);
	$options = $options.option ("Rhode Island", "Rhode Island", $state);
	$options = $options.option ("South Carolina", "South Carolina", $state);
	$options = $options.option ("South Dakota", "South Dakota", $state);
	$options = $options.option ("Tennessee", "Tennessee", $state);
	$options = $options.option ("Texas", "Texas", $state);
	$options = $options.option ("Utah", "Utah", $state);
	$options = $options.option ("Vermont", "Vermont", $state);
	$options = $options.option ("Virginia", "Virginia", $state);
	$options = $options.option ("Virgin Islands", "Virgin Islands", $state);
	$options = $options.option ("Washington", "Washington", $state);
	$options = $options.option ("West Virginia", "West Virginia", $state);
	$options = $options.option ("Wisconsin", "Wisconsin", $state);
	$options = $options.option ("Wyoming", "Wyoming", $state);
	echo $table->tr ($table->td ("Shipping State").
			 $table->td (select ("shipping_state", $options).
				     alert ("Don't lie on this choice.", "?")));
	echo $table->tr ($table->td ("Shipping Zip Code").
			 $table->td (text_input_s ("shipping_zip", $zip, 5, 10).
				     alert ("5 to 10 digits only. e.g. 90210", "?")));
	echo $table->tr ($table->td ("Phone Number").
			 $table->td (text_input_s ("phone", $phone, 12, 12).
				     alert ("10 to 12 characters. e.g. 123-456-7890", "?")));
	echo $table->tr ($table->td ("Email Address").
			 $table->td (text_input_s ("email", $email, 30, 50).
				     alert ("50 characters or less. e.g. bill@hat.com.", "?")));
	$options = "";
	$options = $options.option ("American Express", "American Express", $cc);
	$options = $options.option ("Discover", "Discover", $cc);
	$options = $options.option ("Mastercard", "Mastercard", $cc);
	$options = $options.option ("Visa", "Visa", $cc);
	echo $table->tr ($table->td ("Credit Card Type").
			 $table->td (select ("card_type", $options).
				     alert ("You better have one.", "?")));
	echo $table->tr ($table->td ("Credit Card Number").
			 $table->td (text_input_s ("card_number", $ccn, 16, 16).
				     alert ("12 to 16 digits. e.g. 1111222233334444", "?")));

	// For credit card expiration dates
	$options = "";
	for ($j = 9; $j <= 14; $j++)
	{
		if ($j < 10)
			$j = "0".$j;

		for ($i = 1; $i <= 12; $i++)
		{
			if ($i < 10)
				$i = "0".$i;
			$options = $options.option ("$i/$j", "$i/$j", $ccx);
		}
	}
	echo $table->tr ($table->td ("Credit Card Expiration").
			 $table->td (select ("card_expire", $options).
				     alert ("Expiration date of your credit card.", "?")));

	// Save or register depending on if logged in
	if ($dbinfo->logged_in ())
	{
		echo $table->tr ($table->td_span (submit_input ("Save"), "", 2, "center"));
	}
	else
	{
		echo $table->tr ($table->td_span (submit_input ("Register"), "", 2, "center"));
	}
	echo form_end ();
	echo $table->table_body_end ();
	echo $table->table_end ();
}

// Very simple checking of the form data to meet basic requirements
function verify_data ()
{
	global $dbinfo, $errors, $post_username, $passwd1, $passwd2,
		$is_admin, $post_realname, $post_birth_date, $post_street,
		$post_city, $post_state, $post_zip, $post_phone, $post_email,
		$post_card_type, $post_card_number, $post_card_expire,
		$post_picture, $post_description;
	$errors = "";
	$post_username = post ("username");
	$passwd1 = post ("password");
	$passwd2 = post ("password2");
	$is_admin = post ("is_admin");
	$post_realname = post ("realname");
	$post_birth_date = post ("birth_date");
	$post_street = post ("shipping_street");
	$post_city = post ("shipping_city");
	$post_state = post ("shipping_state");
	$post_zip = post ("shipping_zip");
	$post_phone = post ("phone");
	$post_email = post ("email");
	$post_card_type = post ("card_type");
	$post_card_number = post ("card_number");
	$post_card_expire = post ("card_expire");
	$post_picture = "default_profile.jpg";
	$post_description = "N/A";

	if (empty ($post_username))
		$errors = $errors.li ("Username can't be empty.");
	if (!$dbinfo->logged_in () && $dbinfo->user_exists ($post_username))
		$errors = $errors.li ("Username already exists.");
	if (strlen ($post_username) > 50)
		$errors = $errors.li ("Username must be less than 50 characters.");
	if (empty ($passwd1))
		$errors = $errors.li ("Password can't be empty");
	if ($passwd1 != $passwd2)
		$errors = $errors.li ("Passwords don't match");
	if ($dbinfo->is_admin () && $is_admin == 0 && $dbinfo->username () == $post_username)
	{
		$result = $dbinfo->query ("select username from user where username != '$post_username' AND is_admin = 1");
		if (mysql_num_rows ($result) == 0)
			$errors = $errors.li ("The number of users left that are admins can't be zero.");
	}
	if (empty ($post_realname))
		$errors = $errors.li ("Name can't be empty");
	if (empty ($post_birth_date))
		$errors = $errors.li ("Birth date can't be empty");
	else if (strlen ($post_birth_date) < 6)
		$errors = $errors.li ("Birth date too short");
	if (empty ($post_street))
		$errors = $errors.li ("Street can't be empty");
	if (empty ($post_city))
		$errors = $errors.li ("City can't be empty");
	if ($post_zip + 0 <= 0)
		$errors = $errors.li ("Invalid zip code");
	else if (strlen ($post_zip) < 5)
		$errors = $errors.li ("Zip code too short");
	if (empty ($post_phone))
		$errors = $errors.li ("Phone number can't be empty");
	else if (strlen ($post_phone) < 10)
		$errors = $errors.li ("Phone number too short");
	if (strlen ($post_email) < 5)
		$errors = $errors.li ("Invalid e-mail");
	if ($post_card_number + 0 <= 0)
		$errors = $errors.li ("Invalid credit card number");
	else if (strlen ($post_card_number) < 12)
		$errors = $errors.li ("Credit card number too short");
	return $errors;
}


if ($dbinfo->logged_in ())
{
	$user = $dbinfo->username ();

	// Admin user may be editing/viewing another user's page
	if ($dbinfo->is_admin () && !empty ($get_username))
	{
		$user = $get_username;
	}

	if ($mode == "login_as" && !empty ($get_username))
	{
		$dbinfo->save_activity ("Logged in as '$user'.");
		$dbinfo->login_as ($user);
		echo href ("$current_script?", "Click to refresh.");
	}
	// Admin wants to delete a user
	else if ($mode == "delete" && $dbinfo->is_admin () && !empty ($get_username) && $get_username != $dbinfo->username ())
	{
		$errors = "";
		// check to make sure not last user and that there is at least one admin
		$result = $dbinfo->query ("select username from user where username != '$user' AND is_admin = 1");
		if (mysql_num_rows ($result) == 0)
			$errors = $errors.li ("The number of users left that are admins can't be zero.");
		mysql_free_result ($result);
		// check to make sure not participating in as buyer or seller in any auctions
		$result = $dbinfo->query ("select count(*) from item_listing where seller = '$user' OR buyer = '$user'");
		list ($count) = mysql_fetch_row ($result);
		if ($count != 0)
			$errors = $errors.li ("The user must have not partipated in any auctions as seller or buyer.");
		mysql_free_result ($result);
		if (empty ($errors))
		{
			$dbinfo->save_activity ("Deleted user '$user'.");
			$dbinfo->query ("delete from user_activity where username = '$user'");
			$dbinfo->query ("delete from bids_on where username = '$user'");
			$dbinfo->query ("delete from user where username = '$user'");
			echo "Deleted user. ".href ("$current_script?mode=browse", "Click to browse all users.");
		}
		else
		{
			echo "Errors were detected:<br/>";
			echo ul ($errors);
			echo href ("$current_script?mode=browse", "Click to browse all users.");
		}
	}
	// User wants to delete their account
	else if ($mode == "delete")
	{
		$errors = "";
		// check to make sure not last user and that there is at least one admin
		$result = $dbinfo->query ("select username from user where username != '$user' AND is_admin = 1");
		if (mysql_num_rows ($result) == 0)
			$errors = $errors.li ("The number of users left that are admins can't be zero.");
		mysql_free_result ($result);
		// check to make sure not participating in as buyer or seller in any auctions
		$result = $dbinfo->query ("select count(*) from item_listing where seller = '$user' OR buyer = '$user'");
		list ($count) = mysql_fetch_row ($result);
		if ($count != 0)
			$errors = $errors.li ("You should not have partipated in any auctions as seller or buyer to be deleted.");
		mysql_free_result ($result);
		if (empty ($errors))
		{
			$dbinfo->query ("delete from user_activity where username = '$user'");
			$dbinfo->query ("delete from bids_on where username = '$user'");
			$dbinfo->query ("delete from user where username = '$user'");
			$dbinfo->logout (true);
			echo "Deleted account. ".href ("index.php", "Click to go to the home page.");
		}
		else
		{
			echo "Errors were detected:<br/>";
			echo ul ($errors);
			echo href ("$current_script?mode=view", "Go to your account.");
		}
	}

	// A user is saving updated registration information
	else if ($mode == "save")
	{
		verify_data ();
		if (!empty ($errors))
		{
			echo "Errors were detected. Please correct before continuing:<br/>";
			echo ul ($errors);
			echo href ("$current_script", "Go to your account.");
		}
		else
		{
			// We might be saving information for another user that isn't the current user
			// $post_username will be the current user if not admin since it isn't editable.
			$admin_user = $dbinfo->username ();
			if (empty ($is_admin))
				$is_admin = 0;
			if ($dbinfo->debug ())
			{
				cout ("Debug enabled.");
				cout ("update user set password = '$passwd1', is_admin = $is_admin,
realname = '$post_realname', birth_date = '$post_birth_date', shipping_street = '$post_street', shipping_city = '$post_city',
shipping_state = '$post_state', shipping_zip = $post_zip, phone = '$post_phone', email = '$post_email', card_type = '$post_card_type',
card_number = $post_card_number, card_expire = '$post_card_expire' where username = '$post_username'");
			}
			$dbinfo->query ("update user set password = '$passwd1', is_admin = $is_admin,
realname = '$post_realname', birth_date = '$post_birth_date', shipping_street = '$post_street', shipping_city = '$post_city',
shipping_state = '$post_state', shipping_zip = $post_zip, phone = '$post_phone', email = '$post_email', card_type = '$post_card_type',
card_number = $post_card_number, card_expire = '$post_card_expire' where username = '$post_username'");
			if ($post_username != $admin_user)
			{
				// save activity for both other user and current user
				$dbinfo->save_activity_for ($post_username, "Admin '$admin_user' updated your registration information.");
				$dbinfo->save_activity ("You updated the registration information of '$post_username'.");
			}
			else
			{
				// save current user activity
				$dbinfo->save_activity ("You updated your registration information.");
			}
			// display status
			cout ("Update successful.");
			echo href ("$current_script?mode=view&username=$post_username", "Click to refresh");
		}
	}
	// browse a list of registered users
	else if ($mode == "browse" && $dbinfo->is_admin ())
	{
		echo_div ("scriptstatus");
		echo href ("$current_script?mode=view", "View Your Account");
		end_div ();
		echo h3 ("All Accounts");

		$curr_user = $dbinfo->username ();
		$result = $dbinfo->query ("select u.username, is_admin, realname, email, day, hour, minute
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
			echo $table->tr ($table->td_span ("Registered users of iBay", "", 6));
			echo $table->tr ($table->td ("Username").
					 $table->td ("Realname").
					 $table->td ("Admin?").
					 $table->td ("Email Address").
					 $table->td ("Registration Time").
					 $table->td ("Manage"));
			echo $table->table_head_end ();
			echo $table->table_body_begin ();
			while (list($username, $is_admin, $name, $email, $day, $hour, $min) = mysql_fetch_row ($result))
			{
				echo $table->tr ($table->td ($username).
						 $table->td ($name).
						 $table->td (($is_admin)? "Yes" : "No").
						 $table->td ($email).
						 $table->td (format_time ($day, $hour, $min)).
						 $table->td (href ("$current_script?mode=view&username=$username", "Account").
							     " | ".
							     href ("profile.php?mode=view&username=$username", "Profile").
							     " | ".
							     href ("$current_script?mode=delete&username=$username", "Delete").
							     "<br/>".
							     href ("$current_script?mode=login_as&username=$username", "Login As")));
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
			return;
		}

	}
	else
	{	// browse a registered user's registration information
		if ($dbinfo->is_admin ())
		{
			echo_div ("scriptstatus");
			echo href ("$current_script?mode=browse", "Browse all");
			if ($user != $dbinfo->username ())
			{
				echo " | ";
				echo href ("$current_script?mode=view", "View Your Account");
			}
			end_div ();
		}
		registration_form ($user);
	}
}
else // User is not logged in, display new registration page or save registration
{
	if ($mode == "savenew") // save registration for user
	{
		verify_data ();
		if (!empty ($errors))
		{
			echo "Errors were detected. Please correct before continuing:<br/>";
			echo ul ($errors);
			echo href ("$current_script", "Go to registration.");
		}
		else
		{
			// insert user into the database
			$is_admin = 0;
			$dbinfo->query ("insert into user values ('$post_username', '$passwd1', $is_admin, '$post_realname', '$post_birth_date', '$post_street', '$post_city', '$post_state', $post_zip, '$post_phone', '$post_email', '$post_card_type', $post_card_number, '$post_card_expire', '$post_picture', '$post_description')");
			if (!$dbinfo->user_exists ($post_username))
			{
				cout ("Registration failed. ");
				cout (href (current_script (), "Try again."));
			}
			else
			{
				$dbinfo->save_registration ($post_username);
				$dbinfo->login_as ($post_username);
				cout ("Registration successful.");
				cout ("Click to automatically login and continue to your ".href ("index.php", "home")."?");
			}
		}
	}
	else // display empty form for user to fill out
		registration_form ();
}

echo_footer ($dbinfo);

?>
