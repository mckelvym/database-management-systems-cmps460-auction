<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$mode = get ("mode");
$current_script = current_script ();
$username = $dbinfo->username ();
$curr_day = $dbinfo->day ();
$curr_hour = $dbinfo->hour ();
$curr_minute = $dbinfo->minute ();
$title = get ("title");
$seller = get ("seller");
$category = get ("category");
$end_day = get ("end_day");
$end_hour = get ("end_hour");
$end_minute = get ("end_minute");

if (!$dbinfo->logged_in ())
	redirect ("index.php");

function item_listing_form ($mode = "new", $t = "", $s = "", $c = "", $d = -1, $h = -1, $m = -1)
{
	global $dbinfo;
	global $current_script;
	global $curr_day, $curr_hour, $curr_minute;

	// If editing registration, fetch the current information from the database
	if ($mode == "edit" && $m != -1 && $dbinfo->auction_exists ($t, $s, $c, $d, $h, $m))
	{
		$result = $dbinfo->query ("select * from item_listing
where title = '$t'
AND	seller = '$s'
AND	category = '$c'
AND	end_day = $d
AND	end_hour = $h
AND	end_minute = $m");
		if ($result)
		{
			list ($title, $seller, $category, $end_day, $end_hour, $end_min,
			      $description, $shipping_cost, $shipping_method, $starting_price,
			      $current_price, $picture, $buyer, $buyer_fdbk, $buyer_fdbk_rating,
			      $seller_fdbk)
				= mysql_fetch_row ($result);
		}
		else if ($dbinfo->debug ())
		{
			mysql_free_result ($result);
			die (mysql_error ());
		}
		else
		{
			mysql_free_result ($result);
			echo "Couldn't get auction information.";
			return;
		}
	}
	else if ($mode != "new")
	{
		echo "You've reached this page in error.";
		return;
	}

	// Start writing table to page
	$table = new table_common_t ();
	$table->init ("itemlisting");

	echo $table->table_begin ();
	echo $table->table_head_begin ();
	if ($mode == "edit")
		echo $table->tr ($table->td_span ("Edit your auction", "", 2));
	else
		echo $table->tr ($table->td_span ("Create a new auction", "", 2));

	echo $table->tr_end ();
	echo $table->table_head_end ();
	echo $table->table_body_begin ();

	// Different modes depending on if editing or making new
	if ($mode == "edit")
	{
		echo form_begin ("$current_script?mode=save", "post");
		echo $table->tr ($table->td ("Title<br/>".href ("$current_script?mode=delete&title=$t&seller=$s&category=$c&end_day=$d&end_hour=$h&end_minute=$m", "Delete Auction")).
				 $table->td (text_input_sr ("title", $t, 20, 50)));
		if ($dbinfo->is_admin () && $username != $s)
			echo $table->tr ($table->td ("Seller").
					 $table->td (text_input_sr ("seller", $s, 20, 50)));
		else
			echo hidden_input ("seller", $s);
		echo $table->tr ($table->td ("Category").
				 $table->td (text_input_sr ("category", $c, 20, 50)));
		echo $table->tr ($table->td ("Closing Time").
				 $table->td (text_input_sr ("closing_time", format_time ($d, $h, $m), 20, 50).
					     hidden_input ("end_day", $d).
					     hidden_input ("end_hour", $h).
					     hidden_input ("end_minute", $m)));
		echo $table->tr ($table->td ("Description").
				 $table->td (text_input_s ("description", $description, 50, 250)));
		$options = "";
		$options = $options.option ("FedEx", "FedEx", $shipping_method);
		$options = $options.option ("UPS", "UPS", $shipping_method);
		$options = $options.option ("Air", "Air", $shipping_method);
		echo $table->tr ($table->td ("Shipping Method").
				 $table->td (select ("shipping_method", $options)));
		$options = "";
		for ($i = 1; $i <= 100; $i++)
			$options = $options.option ($i, "\$$i.00", $shipping_cost);
		echo $table->tr ($table->td ("Shipping Cost").
				 $table->td (select ("shipping_cost", $options)));
		echo $table->tr ($table->td ("Starting Price").
				 $table->td ("\$".text_input_sr ("starting_price", $starting_price, 10, 10)));
		$options = "";
		for ($i = 1; $i <= 3; $i++)
		{
			$cat = str_replace (strtolower ($category), "", $picture);
			$options = $options.option ("$i.jpg", "Picture $i", $cat);
		}
		echo $table->tr ($table->td ("Picture").
				 $table->td (select ("picture", $options).
					     href ("category_pictures.php", "?")));
		echo $table->tr ($table->td_span (submit_input ("Save"), "", 2, "center"));
	}
	else
	{
		echo form_begin ("$current_script?mode=savenew", "post");
		echo $table->tr ($table->td ("Title").
				 $table->td (text_input_s ("title", $t, 20, 50)));
		$options = "";
		$options = $options.option ("Art", "Art");
		$options = $options.option ("Books", "Books");
		$options = $options.option ("Clothes", "Clothes");
		$options = $options.option ("Collectibles", "Collectibles");
		$options = $options.option ("Electronics", "Electronics");
		$options = $options.option ("Entertainment", "Entertainment");
		$options = $options.option ("Jewelry", "Jewelry");
		$options = $options.option ("Sporting Goods", "Sporting Goods");
		$options = $options.option ("Toys", "Toys");
		echo $table->tr ($table->td ("Category").
				 $table->td (select ("category", $options)));
		echo $table->tr ($table->td ("Description").
				 $table->td (text_input_s ("description", "", 50, 250)));
		$options = "";
		for ($i = $curr_day; $i <= $curr_day + 7; $i++)
		{
			for ($j = ($i == $curr_day)? $curr_hour + 1 : 0; $j <= 24; $j++)
			{
				$options = $options.option ("$i $j 0", format_time ($i, $j, 0));
			}
		}
		echo $table->tr ($table->td ("Closing Time").
				 $table->td (select ("closing_time", $options)));
		$options = "";
		$options = $options.option ("FedEx", "FedEx");
		$options = $options.option ("UPS", "UPS");
		$options = $options.option ("Air", "Air");
		echo $table->tr ($table->td ("Shipping Method").
				 $table->td (select ("shipping_method", $options)));
		$options = "";
		for ($i = 1; $i <= 100; $i++)
			$options = $options.option ($i, "\$$i.00");
		echo $table->tr ($table->td ("Shipping Cost").
				 $table->td (select ("shipping_cost", $options)));
		$options = "";
		for ($i = 1; $i <= 350; $i++)
		{
			$options = $options.option ("$i.00", "\$$i.00");
			$options = $options.option ("$i.50", "\$$i.50");
		}
		echo $table->tr ($table->td ("Starting Price").
				 $table->td (select ("starting_price", $options)));

		$options = "";
		for ($i = 1; $i <= 3; $i++)
		{
			$options = $options.option ("$i.jpg", "Picture $i");
		}
		echo $table->tr ($table->td ("Picture").
				 $table->td (select ("picture", $options).
					     href ("category_pictures.php", "?")));
		echo $table->tr ($table->td_span (submit_input ("Create"), "", 2, "center"));
	}
	echo form_end ();
	echo $table->table_body_end ();
	echo $table->table_end ();
}

// Very simple checking of the form data to meet basic requirements
function verify_data ()
{
	global $dbinfo, $errors, $post_title, $post_seller,
		$post_category,
		$post_end_day, $post_end_hour, $post_end_minute,
		$post_closing_time, $post_description,
		$post_shipping_method,
		$post_shipping_cost, $post_starting_price,
		$post_picture;
	$errors = "";
	$post_title = post ("title");
	$post_seller = post ("seller");
	$post_category = post ("category");
	$post_closing_time = post ("closing_time");
	$post_end_day = strtok ($post_closing_time, " ");
	$post_end_hour = strtok (" ");
	$post_end_minute = strtok (" ");
	$post_description = post ("description");
	$post_shipping_method = post ("shipping_method");
	$post_shipping_cost = post ("shipping_cost");
	$post_starting_price = post ("starting_price");
	$post_picture = post ("picture");

	if (empty ($post_title))
		$errors = $errors.li ("Title can't be empty.");
	if (strlen ($post_title) > 50)
		$errors = $errors.li ("Title can't more than 50 characters.");
	if (empty ($post_description))
		$errors = $errors.li ("Description can't be empty.");
	if (strlen ($post_description) > 250)
		$errors = $errors.li ("Description can't be more than 250 characters.");
	return $errors;
}

// View an auction listing
if ($mode == "view")
{
	$result = $dbinfo->query ("select * from item_listing
where	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_minute");

	if (mysql_num_rows ($result) == 0)
	{
		cout ("Invalid item listing.");
	}
	else
	{
		list ($title, $seller, $category, $end_day, $end_hour, $end_min,
		      $description, $shipping_cost, $shipping_method, $starting_price,
		      $current_price, $picture, $buyer, $buyer_fdbk, $buyer_fdbk_rating,
		      $seller_fdbk)
			= mysql_fetch_row ($result);
		mysql_free_result ($result);

		$t = new table_common_t ();
		$t->init ("itemlisting");
		$seller_realname = href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller));
		list ($d, $h, $m) = $dbinfo->get_registration_date ($seller);
		$seller_registration = format_time ($d, $h, $m);
		if (is_older ($end_day, $end_hour, $end_minute, $curr_day, $curr_hour, $curr_minute))
		{
			$time_message = $t->tr ($t->td ("Ended on:").
						$t->td (format_time ($end_day, $end_hour, $end_min)));
			$curr_price_message = $t->tr ($t->td ("End Price:").
						      $t->td ("\$$current_price"));
		}
		else
		{
			$time_message = $t->tr ($t->td ("Ends on:").
						$t->td (format_time ($end_day, $end_hour, $end_min)));
			$curr_price_message = $t->tr ($t->td ("Current Price:").
						      $t->td ("\$$current_price"));
		}
		$num_bids = $dbinfo->get_num_bids ($title, $seller, $category, $end_day, $end_hour, $end_min);

		if ($username == $seller || $dbinfo->is_admin ())
		{
			echo_div ("scriptstatus");
			echo href ("$current_script?mode=edit&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_minute", "Edit Listing");
			echo " | ";
			echo href ("$current_script?mode=delete&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_minute", "Delete Listing");
			end_div ();
		}

		echo $t->table_begin ().$t->table_head_begin ().
			$t->tr ($t->td_span ("Auction Listing: \"$title\"", "", 2)).
			$t->table_head_end ().$t->table_body_begin ().
			$t->tr ($t->td_span (local_img ($picture), "", 2, "center")).
			$t->tr ($t->td ("Start price:").
				$t->td ("\$$starting_price")).
			$t->tr ($t->td ("Shipping:").
				$t->td ("\$$shipping_cost ($shipping_method)")).
			$curr_price_message.
			$t->tr ($t->td ("Number of bids:").
				$t->td ("$num_bids")).
			$t->tr ($t->td ("Description:").
				$t->td ($description)).
			$t->tr ($t->td ("Sold by:").
				$t->td ("$seller_realname<br/>(Registered since $seller_registration)")).
			$t->tr ($t->td ("Sold in:").
				$t->td ($category)).
			$time_message.
			$t->table_body_end ().$t->table_end ();

		// Get bid history for item.
		$result = $dbinfo->query ("select username, bid_day, bid_hour, bid_minute, bid_amount from bids_on
where	item_title = '$title'
AND	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_minute
order by bid_amount desc");
		cout ("");
		cout ("Bid history:");
		if (mysql_num_rows ($result) == 0)
		{
			echo div ("$bidder_realname bid \$$bid_amount.", "bidhistory");
		}
		else
		{
			while (list ($bidder, $bid_day, $bid_hour, $bid_minute, $bid_amount)
			       = mysql_fetch_row ($result))
			{
				$bidder_realname = href ("profile.php?mode=view&username=$bidder", $dbinfo->get_realname ($bidder));
				echo div (span (format_time ($bid_day, $bid_hour, $bid_minute), "time").
					  "$bidder_realname bid \$$bid_amount.", "bidhistory");
			}
		}
	}
}
else if ($mode == "new")
{
	item_listing_form ($mode);
}
else if ($mode == "edit")
{
	item_listing_form ($mode, $title, $seller, $category, $end_day, $end_hour, $end_minute);
}
else if ($mode == "savenew")
{
		verify_data ();
		if ($dbinfo->auction_exists ($post_title, $username, $post_category,
					      $post_end_day, $post_end_hour, $post_end_minute))
		{
			$errors = $errors.li ("Auction already exists.");
		}

		if (!empty ($errors))
		{
			echo "Errors were detected. Please correct before continuing:<br/>";
			echo ul ($errors);
			echo href ("$current_script?mode=new", "Go to create a new listing.");
		}
		else
		{
			$post_picture = str_replace (" ", "_", strtolower ($post_category.$post_picture));
			// insert user into the database
			$dbinfo->query ("insert into item_listing values (
'$post_title',
'$username',
'$post_category',
$post_end_day,
$post_end_hour,
$post_end_minute,
'$post_description',
$post_shipping_cost,
'$post_shipping_method',
$starting_price,
$starting_price,
'$post_picture',
'',
'',
-1,
'')");

			if (!$dbinfo->auction_exists ($post_title, $username, $post_category,
						      $post_end_day, $post_end_hour, $post_end_minute))
			{
				cout ("Auction listing failed. ");
				cout (href (current_script (), "Try again."));
			}
			else
			{
				$dbinfo->save_activity ("Listed a new auction: \"".href ("itemlisting.php?mode=view&title=$post_title&seller=$username&category=$post_category&end_day=$post_end_day&end_hour=$post_end_hour&end_minute=$post_end_minute", $post_title)."\".");
				cout ("Auction listing successful.");
				cout ("Would you like to ".href ("itemlisting.php?mode=view&title=$post_title&seller=$username&category=$post_category&end_day=$post_end_day&end_hour=$post_end_hour&end_minute=$post_end_minute", "view")." it?");
			}
		}
}
else if ($mode == "save")
{
		verify_data ();
		if (!empty ($errors))
		{
			echo "Errors were detected. Please correct before continuing:<br/>";
			echo ul ($errors);
		}
		else
		{
			$post_end_day = post ("end_day");
			$post_end_hour = post ("end_hour");
			$post_end_minute = post ("end_minute");
			$post_picture = str_replace (" ", "_", strtolower ($post_category.$post_picture));
			// insert user into the database
			$dbinfo->query ("update item_listing set
description = '$post_description',
shipping_cost = $post_shipping_cost,
shipping_method = '$post_shipping_method',
picture = '$post_picture'
where 	title = '$post_title'
AND	seller = '$post_seller'
AND	category = '$post_category'
AND	end_day = $post_end_day
AND	end_hour = $post_end_hour
AND	end_minute = $post_end_minute");
			$dbinfo->save_activity ("Edited auction: \"".href ("itemlisting.php?mode=view&title=$post_title&seller=$post_seller&category=$post_category&end_day=$post_end_day&end_hour=$post_end_hour&end_minute=$post_end_minute", $post_title)."\".");
				cout ("Auction update successful.");
				cout ("Would you like to ".href ("itemlisting.php?mode=view&title=$post_title&seller=$post_seller&category=$post_category&end_day=$post_end_day&end_hour=$post_end_hour&end_minute=$post_end_minute", "view")." it?");
		}
}
else if ($mode == "delete")
{
	$title = get ("title");
	$seller = get ("seller");
	$category = get ("category");
	$end_day = get ("end_day");
	$end_hour = get ("end_hour");
	$end_minute = get ("end_minute");
	if ($dbinfo->is_admin () || ($seller == $username))
	{
		if ($dbinfo->get_num_bids ($title, $seller, $category,
					   $end_day, $end_hour, $end_minute) == 0)
		{
			$dbinfo->query ("delete from item_listing where
where 	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_minute");
		}
		else
		{
			echo "Errors were detected:<br/>";
			echo ul (li ("Error: There must be no bids for the item listing."));
			cout ("Back to the ".href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_minute", "item").".");
		}
	}
	else
	{
		echo "Errors were detected:<br/>";
		echo ul (li ("Error: Must be an administrator or be the seller for this auction."));
		cout ("Back to the ".href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_minute", "item").".");
	}
}

echo_footer ($dbinfo);

?>
