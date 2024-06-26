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

// Handles item / auction listing related things such as display, bidding,
// editing, deleting






include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

// Javascript functions for validation/etc..
echo "<script type=\"text/javascript\" src=\"itemlisting.js\"></script>";	

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

// Redirect if not logged in
if (!$dbinfo->logged_in ())
	redirect ("index.php");

// Kind of a catch-all form for editing & creating auctions
function item_listing_form ($mode = "new", $t = "", $s = "", $c = "", $d = -1, $h = -1, $m = -1)
{
	global $dbinfo;
	global $current_script;
	global $curr_day, $curr_hour, $curr_minute;

	// If editing auction, fetch the current information from the database
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
	echo h3 ("Auction Management");
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
		echo form_begin_n ("$current_script?mode=save", "post", "item_listing_form", "return validate_form (this)");
		echo $table->tr ($table->td ("Title<br/>".href ("$current_script?mode=delete&title=$t&seller=$s&category=$c&end_day=$d&end_hour=$h&end_minute=$m", "Delete Auction")).
				 $table->td (text_input_sr ("title", $t, 20, 50).
					     alert ("A required field, this is the title of the auction", "?")));
		if ($dbinfo->is_admin () && $username != $s)
			echo $table->tr ($table->td ("Seller").
					 $table->td (text_input_sr ("seller", $s, 20, 50).
						     alert ("The person who initiated the auction.", "?")));
		else
			echo hidden_input ("seller", $s);
		echo $table->tr ($table->td ("Category").
				 $table->td (input ("category", $c, 20, "dyncategory", "text", 50, "readonly").
					     alert ("An auction can only belong to one category.", "?")));
		echo $table->tr ($table->td ("Description").
				 $table->td (text_input_s ("description", $description, 50, 250).
					     alert ("Under no circumstances leave this blank. Be....descriptive", "?")));
		echo $table->tr ($table->td ("Closing Time").
				 $table->td (text_input_sr ("closing_time", format_time ($d, $h, $m), 20, 50).
					     alert ("Up to 10 days in advance. When do you want the fun to end?", "?").
					     hidden_input ("end_day", $d).
					     hidden_input ("end_hour", $h).
					     hidden_input ("end_minute", $m)));
		$options = "";
		$options = $options.option ("FedEx", "FedEx", $shipping_method);
		$options = $options.option ("UPS", "UPS", $shipping_method);
		$options = $options.option ("Air", "Air", $shipping_method);
		echo $table->tr ($table->td ("Shipping Method").
				 $table->td (select ("shipping_method", $options).
					     alert ("Sorry, we no longer ship via horse and carriage.", "?")));
		$options = "";
		for ($i = 1; $i <= 100; $i++)
			$options = $options.option ($i, "\$$i.00", $shipping_cost);
		echo $table->tr ($table->td ("Shipping Cost").
				 $table->td (select ("shipping_cost", $options).
					     alert ("Your best estimate please.", "?")));
		echo $table->tr ($table->td ("Starting Price").
				 $table->td ("\$".text_input_sr ("starting_price", $starting_price, 10, 10).
					     alert ("Be competitive.", "?")));
		$options = "";
		for ($i = 1; $i <= 3; $i++)
		{
			$cat = str_replace (str_replace (" ", "_", strtolower ($category)), "", $picture);
			$options = $options.option ("$i.jpg", "Picture $i", $cat);
		}
		echo $table->tr ($table->td ("Picture").
				 $table->td (select_dyn ("picture", $options, "image_swap()").
					     hreft ("category_pictures.php", "?", "_blank")."<br/>".
					     local_img ($picture)));
		echo $table->tr ($table->td_span (submit_input ("Save"), "", 2, "center"));
	}
	else // making new listing
	{
		echo form_begin_n ("$current_script?mode=savenew", "post", "item_listing_form", "return validate_form (this)");
		echo $table->tr ($table->td ("Title").
				 $table->td (text_input_s ("title", $t, 20, 50).
					     alert ("A required field, this is the title of the auction", "?")));
		$options = "";
		$options = $options.option ("Art", "Art");
		$options = $options.option ("Books", "Books");
		$options = $options.option ("Clothing", "Clothing");
		$options = $options.option ("Collectibles", "Collectibles");
		$options = $options.option ("Electronics", "Electronics");
		$options = $options.option ("Entertainment", "Entertainment");
		$options = $options.option ("Jewelry", "Jewelry");
		$options = $options.option ("Sporting Goods", "Sporting Goods");
		$options = $options.option ("Toys", "Toys");
		echo $table->tr ($table->td ("Category").
				 $table->td (select_dyn ("category", $options, "image_swap()").
					     alert ("An auction can only belong to one category.", "?")));
		echo $table->tr ($table->td ("Description").
				 $table->td (text_input_s ("description", "", 50, 250).
					     alert ("Under no circumstances leave this blank. Be....descriptive", "?")));
		$options = "";
		for ($i = $curr_day; $i <= $curr_day + 7; $i++)
		{
			for ($j = ($i == $curr_day)? $curr_hour + 1 : 0; $j < 24; $j++)
			{
				$options = $options.option ("$i $j 0", format_time ($i, $j, 0));
			}
		}
		echo $table->tr ($table->td ("Closing Time").
				 $table->td (select ("closing_time", $options).
					     alert ("Up to 10 days in advance. When do you want the fun to end?", "?")));
		$options = "";
		$options = $options.option ("FedEx", "FedEx");
		$options = $options.option ("UPS", "UPS");
		$options = $options.option ("Air", "Air");
		echo $table->tr ($table->td ("Shipping Method").
				 $table->td (select ("shipping_method", $options).
					     alert ("Sorry, we no longer ship via horse and carriage.", "?")));
		$options = "";
		for ($i = 1; $i <= 100; $i++)
			$options = $options.option ($i, "\$$i.00");
		echo $table->tr ($table->td ("Shipping Cost").
				 $table->td (select ("shipping_cost", $options).
					     alert ("Your best estimate please.", "?")));
		$options = "";
		for ($i = 1; $i <= 350; $i++)
		{
			$options = $options.option ("$i.00", "\$$i.00");
			$options = $options.option ("$i.50", "\$$i.50");
		}
		echo $table->tr ($table->td ("Starting Price").
				 $table->td (select ("starting_price", $options).
					     alert ("Be competitive.", "?")));

		$options = "";
		for ($i = 1; $i <= 3; $i++)
		{
			$options = $options.option ("$i.jpg", "Picture $i");
		}
		echo $table->tr ($table->td ("Picture").
				 $table->td (select_dyn ("picture", $options, "image_swap()").
					     hreft ("category_pictures.php", "?", "_blank")."<br/>".
					     local_img ("art1.jpg")));
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
	$post_title = fix_quotes (post ("title"));
	$post_seller = post ("seller");
	$post_category = post ("category");
	$post_closing_time = post ("closing_time");
	$post_end_day = strtok ($post_closing_time, " ");
	$post_end_hour = strtok (" ");
	$post_end_minute = strtok (" ");
	$post_description = fix_quotes (post ("description"));
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

// Handle bid
if ($mode == "bid")
{
	$bid_title = (post ("title"));
	$bid_seller = post ("seller");
	$bid_category = post ("category");
	$bid_end_day = post ("end_day");
	$bid_end_hour = post ("end_hour");
	$bid_end_minute = post ("end_minute");
	$bid_amount = post ("bid_amount");

	list ($highest_bidder, $amt) = $dbinfo->get_highest_bidder ($bid_title, $bid_seller, $bid_category,
								    $bid_end_day, $bid_end_hour, $bid_end_minute);
	if ($highest_bidder != $username)
	{
		$dbinfo->update_auction_before_bid (
			$bid_title, $bid_seller, $bid_category,
			$bid_end_day, $bid_end_hour, $bid_end_minute,
			$bid_amount);
		$dbinfo->query ("insert into bids_on values ('$username', '$bid_title', '$bid_seller', '$bid_category', $bid_end_day, $bid_end_hour, $bid_end_minute, $curr_day, $curr_hour, $curr_minute, $bid_amount, 'n')");
		$dbinfo->save_activity ("Bid \$$bid_amount on the auction: \"".href ("itemlisting.php?mode=view&title=$bid_title&seller=$bid_seller&category=$bid_category&end_day=$bid_end_day&end_hour=$bid_end_hour&end_minute=$bid_end_minute", $bid_title)."\".");
	}
	else
	{
		echo "Errors were detected. Please correct before continuing:<br/>";
		echo ul (li ("Don't double bid!"));
		$dbinfo->save_activity ("You tried to game the system by double bidding \$$bid_amount on the auction: \"".href ("itemlisting.php?mode=view&title=$bid_title&seller=$bid_seller&category=$bid_category&end_day=$bid_end_day&end_hour=$bid_end_hour&end_minute=$bid_end_minute", $bid_title)."\".");
	}

	$mode = "view";
}

// View an auction listing
if ($mode == "view")
{
	$sfdbk = get ("sfdbk");
	$bfdbk = get ("bfdbk");
	if ($dbinfo->is_admin () && !empty ($sfdbk))
	{
		$dbinfo->query ("update item_listing set
sellerfeedbackforbuyer_description = ''
where	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_minute");
	}
	if ($dbinfo->is_admin () && !empty ($bfdbk))
	{
		$dbinfo->query ("update item_listing set
buyerfeedbackforseller_description = '',
buyerfeedbackforseller_rating = -1
where	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_minute");
	}

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
		$closed = is_older ($end_day, $end_hour, $end_minute, $curr_day, $curr_hour, $curr_minute);
		if ($closed)
		{
			$time_message = $t->tr ($t->td ("Ended on:").
						$t->td (format_time ($end_day, $end_hour, $end_min)));
			$curr_price_message = $t->tr ($t->td ("End Price:").
						      $t->td ("\$$current_price"));
			// feedback
			if ($buyer_fdbk_rating != -1)
			{
				if ($dbinfo->is_admin ())
				{
					$url = "$current_script?";
					foreach ($_GET as $var => $val)
						$url = $url."$var=$val&";
					$url = $url."bfdbk=delete";
					$fdbk = $t->tr ($t->td ("Feedback from buyer:<br/>".
								href ($url, "Delete")).
							$t->td ($buyer_fdbk." Rating: $buyer_fdbk_rating"));
				}
				else
				{
					$fdbk = $t->tr ($t->td ("Feedback from buyer:").
							$t->td ($buyer_fdbk." Rating: $buyer_fdbk_rating"));
				}
			}
			else
			{
				$fdbk = $t->tr ($t->td ("Feedback from buyer:").
						$t->td ("None."));
			}
			// feedback
			if (!empty ($seller_fdbk))
			{
				if ($dbinfo->is_admin ())
				{
					$url = "$current_script?";
					foreach ($_GET as $var => $val)
						$url = $url."$var=$val&";
					$url = $url."sfdbk=delete";
					$fdbk = $fdbk.$t->tr ($t->td ("Feedback from seller:<br/>".
								      href ($url, "Delete")).
							      $t->td ($seller_fdbk));
				}
				else
				{
					$fdbk = $fdbk.$t->tr ($t->td ("Feedback from seller:").
							      $t->td ($seller_fdbk));
				}
			}
			else
			{
				$fdbk = $fdbk.$t->tr ($t->td ("Feedback from seller:").
						      $t->td ("None."));
			}
		}
		else
		{
			$time_message = $t->tr ($t->td ("Ends on:").
						$t->td (format_time ($end_day, $end_hour, $end_min)));
			list ($highest_bidder, $highest_bid) =
				$dbinfo->get_highest_bidder ($title, $seller, $category, $end_day, $end_hour, $end_min);
			if ($highest_bidder == "None")
				$highest_bid = $current_price;

			// bid form
			if ($username != $seller &&
			    $username != $highest_bidder)
			{
				$options = "";
				for ($i = $highest_bid + 0.50; $i <= $highest_bid + 50; $i += 0.50)
				{
					$i = sprintf ("%.2f", $i);
					$options = $options.option ($i, "\$$i");
				}
				$url = "$current_script?";
				foreach ($_GET as $var => $val)
					$url = $url."$var=$val&";
				$url = $url."mode=bid";
				$curr_price_message = $t->tr ($t->td ("Current Price:").
							      $t->td ("\$$current_price".
								      form_begin ("$url", "post").
								      select ("bid_amount", $options).
								      hidden_input ("title", $title).
								      hidden_input ("seller", $seller).
								      hidden_input ("category", $category).
								      hidden_input ("end_day", $end_day).
								      hidden_input ("end_hour", $end_hour).
								      hidden_input ("end_minute", $end_min).
								      submit_input ("Place Bid").
								      form_end ()));
			}
			else
			{
				$curr_price_message = $t->tr ($t->td ("Current Price:").
							      $t->td ("\$$current_price"));
			}
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

		// output auction info
		echo $t->table_begin ().$t->table_head_begin ().
			$t->tr ($t->td_span ("Auction Listing: \"".$title."\"", "", 2)).
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
			$fdbk.
			$t->table_body_end ().$t->table_end ();

		// Get bid history for item.
		if (false && $dbinfo->debug ())
		{
			cout ("Debug enabled.");
			cout ("select username, bid_day, bid_hour, bid_minute, bid_amount from bids_on
where	item_title = '$title'
AND	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_minute
order by bid_amount desc");
		}
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
			echo div ("None.", "bidhistory");
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
else if ($mode == "new") // make a new auction listing
{
	item_listing_form ($mode);
}
else if ($mode == "edit") // edit an auction listing
{
	item_listing_form ($mode, $title, $seller, $category, $end_day, $end_hour, $end_minute);
}
else if ($mode == "savenew") // save a new auction listing
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

			// insert item into the database
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
$post_starting_price,
$post_starting_price,
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
else if ($mode == "save") // save an editing (current) auction listing
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
			// update item in the database

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
			if ($dbinfo->is_admin () && $post_seller != $username)
			{
				$realname = href ("profile.php?mode=view&username=$username", $dbinfo->realname ());
				$dbinfo->save_activity_for ($post_seller, "$realname edited your auction: \"".href ("itemlisting.php?mode=view&title=$post_title&seller=$post_seller&category=$post_category&end_day=$post_end_day&end_hour=$post_end_hour&end_minute=$post_end_minute", $post_title)."\".");
			}
			cout ("Auction update successful.");
			cout ("Would you like to ".href ("itemlisting.php?mode=view&title=$post_title&seller=$post_seller&category=$post_category&end_day=$post_end_day&end_hour=$post_end_hour&end_minute=$post_end_minute", "view")." it?");
		}
}
else if ($mode == "delete") // delete an exiting auction listing
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
			$dbinfo->query ("delete from item_listing
where 	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_minute");
			$dbinfo->save_activity ("Deleted the auction \"$title\".");
			if ($dbinfo->is_admin () && ($seller != $username))
			{
				$realname = href ("profile.php?mode=view&username=$username", $dbinfo->realname ());
				$dbinfo->save_activity_for ($seller, "$realname deleted the auction \"$title\".");
			}
			cout ("Back to your ".href ("index.php", "home").".");
		}
		else
		{
			echo "Errors were detected:<br/>";
			echo ul (li ("There must be no bids for the item listing."));
			cout ("Back to the ".href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_minute", "item").".");
		}
	}
	else
	{
		echo "Errors were detected:<br/>";
		echo ul (li ("Must be an administrator or be the seller for this auction."));
		cout ("Back to the ".href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_minute", "item").".");
	}
}

echo_footer ($dbinfo);
?>
