<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$mode = get ("mode");
$current_script = current_script ();

if ($mode == "edit")
	$username = get ("user");

function new_registration_form ($username = "")
{
	global $dbinfo;
	$table = new table_common_t ();
	$table->init ("tbl_std");

	echo $table->table_begin ();
	echo $table->table_head_begin ();
	echo $table->tr ($table->td_span ("Register for an iBay account", "", 2));
	echo $table->tr_end ();
	echo $table->table_body_begin ();
	if ($dbinfo->is_admin ())
		echo form_begin ("$current_script?mode=save", "post");
	else
		echo form_begin ("$current_script?mode=savenew", "post");
	echo $table->tr ($table->td ("Desired Username").
			 $table->td (text_input_s ("username", "", 20, 50)));
	echo $table->tr ($table->td ("Desired Password").
			 $table->td (password_input_s ("password", "", 20, 50)));
	echo $table->tr ($table->td ("Repeat Password").
			 $table->td (password_input_s ("password2", "", 20, 50)));
	if ($dbinfo->is_admin ())
	{
		$options = "";
		$options = $options.option ("0", "No");
		$options = $options.option ("1", "Yes");
		echo $table->tr ($table->td ("Admin").
				 $table->td (select ("is_admin", "1", $options)));
	}
	echo $table->tr ($table->td ("Real Name").
			 $table->td (text_input_s ("realname", "", 20, 100)));
	echo $table->tr ($table->td ("Birth Date").
			 $table->td (text_input_s ("birth_date", "", 10, 10)));
	echo $table->tr ($table->td ("Shipping Street").
			 $table->td (text_input_s ("shipping_street", "", 20, 100)));
	echo $table->tr ($table->td ("Shipping City").
			 $table->td (text_input_s ("shipping_city", "", 20, 50)));
	$options = "";
	$options = $options.option ("Alabama", "Alabama");
	$options = $options.option ("Alaska", "Alaska");
	$options = $options.option ("American Samoa", "American Samoa");
	$options = $options.option ("Arizona", "Arizona");
	$options = $options.option ("Arkansas", "Arkansas");
	$options = $options.option ("California", "California");
	$options = $options.option ("Colorado", "Colorado");
	$options = $options.option ("Connecticut", "Connecticut");
	$options = $options.option ("Delaware", "Delaware");
	$options = $options.option ("District of Columbia", "District of Columbia");
	$options = $options.option ("Florida", "Florida");
	$options = $options.option ("Georgia", "Georgia");
	$options = $options.option ("Guam", "Guam");
	$options = $options.option ("Hawaii", "Hawaii");
	$options = $options.option ("Idaho", "Idaho");
	$options = $options.option ("Illinois", "Illinois");
	$options = $options.option ("Indiana", "Indiana");
	$options = $options.option ("Iowa", "Iowa");
	$options = $options.option ("Kansas", "Kansas");
	$options = $options.option ("Kentucky", "Kentucky");
	$options = $options.option ("Louisiana", "Louisiana");
	$options = $options.option ("Maine", "Maine");
	$options = $options.option ("Maryland", "Maryland");
	$options = $options.option ("Massachusetts", "Massachusetts");
	$options = $options.option ("Michigan", "Michigan");
	$options = $options.option ("Minnesota", "Minnesota");
	$options = $options.option ("Mississippi", "Mississippi");
	$options = $options.option ("Missouri", "Missouri");
	$options = $options.option ("Montana", "Montana");
	$options = $options.option ("Nebraska", "Nebraska");
	$options = $options.option ("Nevada", "Nevada");
	$options = $options.option ("New Hampshire", "New Hampshire");
	$options = $options.option ("New Jersey", "New Jersey");
	$options = $options.option ("New Mexico", "New Mexico");
	$options = $options.option ("New York", "New York");
	$options = $options.option ("North Carolina", "North Carolina");
	$options = $options.option ("North Dakota", "North Dakota");
	$options = $options.option ("Northern Marianas Islands", "Northern Marianas Islands");
	$options = $options.option ("Ohio", "Ohio");
	$options = $options.option ("Oklahoma", "Oklahoma");
	$options = $options.option ("Oregon", "Oregon");
	$options = $options.option ("Pennsylvania", "Pennsylvania");
	$options = $options.option ("Puerto Rico", "Puerto Rico");
	$options = $options.option ("Rhode Island", "Rhode Island");
	$options = $options.option ("South Carolina", "South Carolina");
	$options = $options.option ("South Dakota", "South Dakota");
	$options = $options.option ("Tennessee", "Tennessee");
	$options = $options.option ("Texas", "Texas");
	$options = $options.option ("Utah", "Utah");
	$options = $options.option ("Vermont", "Vermont");
	$options = $options.option ("Virginia", "Virginia");
	$options = $options.option ("Virgin Islands", "Virgin Islands");
	$options = $options.option ("Washington", "Washington");
	$options = $options.option ("West Virginia", "West Virginia");
	$options = $options.option ("Wisconsin", "Wisconsin");
	$options = $options.option ("Wyoming", "Wyoming");
	echo $table->tr ($table->td ("Shipping State").
			 $table->td (select ("shipping_state", "", $options)));
	echo $table->tr ($table->td ("Shipping Zip Code").
			 $table->td (text_input_s ("shipping_zip", "", 5, 10)));
	echo $table->tr ($table->td ("Phone Number").
			 $table->td (text_input_s ("phone", "", 12, 12)));
	echo $table->tr ($table->td ("Email Address").
			 $table->td (text_input_s ("email", "", 20, 50)));
	$options = "";
	$options = $options.option ("American Express", "American Express");
	$options = $options.option ("Discover", "Discover");
	$options = $options.option ("Mastercard", "Mastercard");
	$options = $options.option ("Visa", "Visa");
	echo $table->tr ($table->td ("Credit Card Type").
			 $table->td (select ("card_type", "", $options)));
	echo $table->tr ($table->td ("Credit Card Number").
			 $table->td (text_input_s ("card_number", "", 16, 16)));
	$options = "";

	for ($j = 9; $j <= 14; $j++)
	{
		if ($j < 10)
			$j = "0".$j;

		for ($i = 1; $i <= 12; $i++)
		{
			if ($i < 10)
				$i = "0".$i;
			$options = $options.option ("$i/$j", "$i/$j");
		}
	}
	echo $table->tr ($table->td ("Credit Card Expiration").
			 $table->td (select ("card_expire", "", $options)));
	$options = "";
	$options = $options.option ("default.jpg", "Default");
	$options = $options.option ("smile.jpg", "Smile");
	$options = $options.option ("frown.jpg", "Frown");
	echo $table->tr ($table->td ("Profile Picture").
			 $table->td (select ("picture", "", $options)));
	if ($dbinfo->is_admin ())
		echo $table->tr ($table->td_span (submit_input ("Save"), "", 2));
	else
		echo $table->tr ($table->td_span (submit_input ("Register"), "", 2));
	echo form_end ();
	echo $table->table_body_end ();
	echo $table->table_end ();
}


if ($dbinfo->logged_in ())
{
	if ($dbinfo->is_admin ())
	{
		echo "Soon, you'll see the admin registration panel.";
	}
	else
	{
		echo "Soon, you'll see your registration info.";
	}
	new_registration_form ($username);
}
else
{
	if ($mode == "savenew")
	{
		$errors = "";
		$username = post ("username");
		$passwd1 = post ("password");
		$passwd2 = post ("password2");
		$is_admin = 0;
		$name = post ("realname");
		$birth = post ("birth_date");
		$street = post ("shipping_street");
		$city = post ("shipping_city");
		$state = post ("shipping_state");
		$zip = post ("shipping_zip");
		$phone = post ("phone");
		$email = post ("email");
		$cc_type = post ("card_type");
		$cc_num = post ("card_number");
		$cc_exp = post ("card_expire");
		$pic = post ("picture");
		$desc = "N/A";

		if (empty ($username))
			$errors = $errors.li ("Username can't be empty");
		if (!$dbinfo->user_exists ($username))
			$errors = $errors.li ("Username already exists");
		if (empty ($passwd1))
			$errors = $errors.li ("Password can't be empty");
		if ($passwd1 != $passwd2)
			$errors = $errors.li ("Passwords don't match");
		if (empty ($name))
			$errors = $errors.li ("Name can't be empty");
		if (empty ($birth))
			$errors = $errors.li ("Birth date can't be empty");
		else if (strlen ($birth) < 6)
			$errors = $errors.li ("Birth date too short");
		if (empty ($street))
			$errors = $errors.li ("Street can't be empty");
		if (empty ($city))
			$errors = $errors.li ("City can't be empty");
		if (!filter_var ($zip, FILTER_VALIDATE_INT))
			$errors = $errors.li ("Invalid zip code");
		else if (strlen ($zip) < 5)
			$errors = $errors.li ("Zip code too short");
		if (empty ($phone))
			$errors = $errors.li ("Phone number can't be empty");
		else if (strlen ($phone) < 10)
			$errors = $errors.li ("Phone number too short");
		if (!filter_var ($email, FILTER_VALIDATE_EMAIL))
			$errors = $errors.li ("Invalid e-mail");
		if ($cc_num + 0 <= 0)
			$errors = $errors.li ("Invalid credit card number");
		else if (strlen ($cc_num) < 12)
			$errors = $errors.li ("Credit card number too short");
		if (!empty ($errors))
		{
			echo "Errors were detected. Please correct before continuing:<br/>";
			echo ul ($errors);
		}
		else
		{
			$dbinfo->query ("insert into user values ('$username', '$passwd1', $is_admin, '$name', '$birth', '$street', '$city', '$state', $zip, '$phone', '$email', '$cc_type', $cc_num, '$cc_exp', '$pic', '$desc')");
			if (!$dbinfo->user_exists ($username))
			{
				cout ("Registration failed. ");
				cout (href ($current_script, "Try again."));
			}
			else
			{
				cout ("Registration successful.");
				cout ("Would you like to ".href ("login.php", "login")."?");
			}
		}
	}
	else
		new_registration_form ();
}

echo_footer ($dbinfo);

?>
