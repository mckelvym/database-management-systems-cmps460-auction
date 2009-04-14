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

$x = 0;
$output;

// Remove an outbid notification
if ($dbinfo->logged_in () && $mode == "remove")
{
	$title = get ("t");
	$seller = get ("s");
	$category = get ("c");
	$end_day = get ("d");
	$end_hour = get ("h");
	$end_min = get ("m");
	$bid_day = get ("bd");
	$bid_hour = get ("bh");
	$bid_min = get ("bm");
	$dbinfo->query ("update bids_on set display_notification = 'c'
where 	username = '$username'
AND	item_title = '$title'
AND	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_min
AND	bid_day = $bid_day
AND	bid_hour = $bid_hour
AND	bid_minute = $bid_min");
}

// Save feedback
if ($dbinfo->logged_in () && $mode == "feedback")
{
	// Need to save feedback for user.
	$title = post ("title");
	$seller = post ("seller");
	$category = post ("category");
	$end_day = post ("end_day");
	$end_hour = post ("end_hour");
	$end_min = post ("end_min");
	$description = post ("feedback");
	$rating = post ("rating");
	$for = post ("for");
	$real_title = href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title);
	if ($for == "buyer")
	{
		$dbinfo->query ("update item_listing set sellerfeedbackforbuyer_description = '$description'
where 	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_min");
		$dbinfo->save_activity ("You left feedback for the winner of your auction \"$real_title\"");
	}
	if ($for == "seller")
	{
		$dbinfo->query ("update item_listing set buyerfeedbackforseller_description = '$description',
buyerfeedbackforseller_rating = $rating
where 	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_min");
		$dbinfo->save_activity ("You left feedback for the seller of the auction \"$real_title\"");
	}
}

// The big mambo jambo
if ($dbinfo->logged_in ())
{
	// Get all feedback notifications which you are seller for
	$result = $dbinfo->query ("select * from item_listing
where 	seller = '$username'
AND 	(end_day < $curr_day
	OR (end_day = $curr_day
		AND end_hour < $curr_hour)
	OR (end_day = $curr_day
		AND end_hour = $curr_hour
		AND end_minute < $curr_minute))
AND	(buyer <> 'None')
order by end_day desc, end_hour desc, end_minute desc");
	$t = new table_common_t ();
	$t->init ("feedback");
	while (list ($title, $seller, $category, $end_day, $end_hour, $end_min,
		     $description, $shipping_cost, $shipping_method, $starting_price,
		     $current_price, $picture, $buyer, $buyer_fdbk, $buyer_fdbk_rating,
		     $seller_fdbk)
	       = mysql_fetch_row ($result))
	{
		$real_title = href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title);
		$winner_realname = href ("profile.php?mode=view&username=$buyer", $dbinfo->get_realname ($buyer));
		if (empty ($buyer_fdbk))
		{
			$bfdbk = "$winner_realname has not left any feedback for you.<br/>";
		}
		else
		{
			$bfdbk = "$winner_realname has given you a rating of \"$buyer_fdbk_rating\"
and has left the following feedback: \"$buyer_fdbk\"";
		}

		if (empty ($seller_fdbk))
			$sfdbk = $t->table_begin ().$t->table_head_begin ().
				$t->tr ($t->td ("Leave feedback for $winner_realname?")).
				$t->table_head_end ().$t->table_body_begin ().
				form_begin ("$current_script?mode=feedback", "post").
				$t->tr ($t->td (text_input_s ("feedback", "", 50, 250).
						hidden_input ("title", $title).
						hidden_input ("seller", $seller).
						hidden_input ("category", $category).
						hidden_input ("end_day", $end_day).
						hidden_input ("end_hour", $end_hour).
						hidden_input ("end_min", $end_min).
						hidden_input ("for", "buyer")).
					$t->td_span (submit_input ("Save"), "", 1, "center")).
				form_end ().
				$t->table_body_end ().$t->table_end ();
		else
			$sfdbk = "<br/>You left the following feedback: \"$seller_fdbk\".";

		$output[$x] = pad_time ($end_day, $end_hour, $end_min, 5).div (span (format_time ($end_day, $end_hour, $end_min), "time").
			  "Regarding your auction \"$real_title\" which has ended, $winner_realname won with a high bid of \$$current_price.<br/>$bfdbk$sfdbk", "feedback");
		$x++;
	}
	mysql_free_result ($result);


	// Get all feedback notifications which you are buyer for
	$result = $dbinfo->query ("select * from item_listing
where 	buyer = '$username'
order by end_day desc, end_hour desc, end_minute desc");
	while (list ($title, $seller, $category, $end_day, $end_hour, $end_min,
		     $description, $shipping_cost, $shipping_method, $starting_price,
		     $current_price, $picture, $buyer, $buyer_fdbk, $buyer_fdbk_rating,
		     $seller_fdbk)
	       = mysql_fetch_row ($result))
	{
		$real_title = href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title);
		$seller_realname = href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller));

		if (empty ($seller_fdbk))
			$sfdbk = "$seller_realname has not left any feedback for you.<br/>";
		else
			$sfdbk = "$seller_realname has left the following feedback: \"$seller_fdbk\"";

		if (empty ($buyer_fdbk))
		{
			$options = "";
			for ($i = 10; $i >= 0; $i--)
			{
				$options = $options.option ($i, $i, "");
			}
			$bfdbk = $t->table_begin ().$t->table_head_begin ().
				$t->tr ($t->td ("Leave feedback for $seller_realname?")).
				$t->table_head_end ().$t->table_body_begin ().
				form_begin ("$current_script?mode=feedback", "post").
				$t->tr ($t->td (text_input_s ("feedback", "", 50, 250).
						hidden_input ("title", $title).
						hidden_input ("seller", $seller).
						hidden_input ("category", $category).
						hidden_input ("end_day", $end_day).
						hidden_input ("end_hour", $end_hour).
						hidden_input ("end_min", $end_min).
						hidden_input ("for", "seller")).
					$t->td (select ("rating", $options)).
					$t->td_span (submit_input ("Save"), "", 1, "center")).
				form_end ().
				$t->table_body_end ().$t->table_end ();
		}
		else
		{
			$bfdbk = "<br/>You rated $seller_realname '$buyer_fdbk_rating' and left the following feedback: \"$buyer_fdbk\".";
		}

		$output[$x] = pad_time ($end_day, $end_hour, $end_min, 5).div (span (format_time ($end_day, $end_hour, $end_min), "time").
			  "Regarding the auction \"$real_title\" you have won with a high bid of \$$current_price.<br/>$sfdbk$bfdbk", "feedback");
		$x++;
	}
	mysql_free_result ($result);

	// Get all closed auction notifications
	list ($lastvisit_day, $lastvisit_hour, $lastvisit_min) = $dbinfo->get_lastlogout ($username);
	if ($lastvisit_day != -1)
	{
		// Get closed auctions they have bid on, since last visit
		$result = $dbinfo->query ("select * from bids_on
where 	username = '$username'
AND 	(item_end_day < $curr_day
	OR (item_end_day = $curr_day
		AND item_end_hour < $curr_hour)
	OR (item_end_day = $curr_day
		AND item_end_hour = $curr_hour
		AND item_end_minute < $curr_minute))
AND 	(item_end_day > $lastvisit_day
	OR (item_end_day = $lastvisit_day
		AND item_end_hour > $lastvisit_hour)
	OR (item_end_day = $lastvisit_day
		AND item_end_hour = $lastvisit_hour
		AND item_end_minute >= $lastvisit_min))
order by item_end_day desc, item_end_hour desc, item_end_minute desc");
		while (list ($user, $title, $seller, $category, $end_day, $end_hour, $end_min,
			     $bid_day, $bid_hour, $bid_min, $bid_amt)
		       = mysql_fetch_row ($result))
		{
			$real_title = href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title);
			$seller_realname = href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller));
			list ($winner, $winner_realname, $win_price) =
				$dbinfo->get_winner ($title, $seller, $category, $end_day, $end_hour, $end_min);
			if ($winner != -1 && $winner != $username)
			{
				$winner_realname = href ("profile.php?mode=view&username=$winner", $winner_realname);
				$output[$x] = pad_time ($end_day, $end_hour, $end_min, 4).div (span (format_time ($end_day, $end_hour, $end_min), "time").
					  "The auction \"$real_title\" by $seller_realname that you have partipated in has ended since your last visit. ".
					  "$winner_realname won the auction with a bid of \$$win_price.", "closed");
				$x++;
			}
			else
			{
				$winner_realname = href ("profile.php?mode=view&username=$winner", $winner_realname);
				$output[$x] = pad_time ($end_day, $end_hour, $end_min, 4).div (span (format_time ($end_day, $end_hour, $end_min), "time").
					  "The auction \"$real_title\" by $seller_realname that you have partipated in has ended since your last visit. ".
					  "You won the auction with a bid of \$$win_price.", "closed");
				$x++;
			}
		}
		mysql_free_result ($result);

		// Get closed auctions they are the seller for
		$result = $dbinfo->query ("select * from item_listing
where 	seller = '$username'
AND 	(end_day < $curr_day
	OR (end_day = $curr_day
		AND end_hour < $curr_hour)
	OR (end_day = $curr_day
		AND end_hour = $curr_hour
		AND end_minute < $curr_minute))
AND 	(end_day > $lastvisit_day
	OR (end_day = $lastvisit_day
		AND end_hour > $lastvisit_hour)
	OR (end_day = $lastvisit_day
		AND end_hour = $lastvisit_hour
		AND end_minute >= $lastvisit_min))
order by end_day desc, end_hour desc, end_minute desc");
		$t = new table_common_t ();
		$t->init ("feedback");
		while (list ($title, $seller, $category, $end_day, $end_hour, $end_min,
			     $description, $shipping_cost, $shipping_method, $starting_price,
			     $current_price, $picture, $buyer, $buyer_fdbk, $buyer_fdbk_rating,
			     $seller_fdbk)
		       = mysql_fetch_row ($result))
		{
			$real_title = href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title);
			if ($buyer == "None")
			{
				$output[$x] = pad_time ($end_day, $end_hour, $end_min, 4).div (span (format_time ($end_day, $end_hour, $end_min), "time").
					  "Your auction \"$real_title\" has ended since your last visit with no buyer.", "closed");
				$x++;
			}
			else
			{
				$winner_realname = href ("profile.php?mode=view&username=$buyer", $dbinfo->get_realname ($buyer));
				$output[$x] = pad_time ($end_day, $end_hour, $end_min, 4).div (span (format_time ($end_day, $end_hour, $end_min), "time").
					  "Your auction \"$real_title\" has ended since your last visit, with
$winner_realname winning with a high bid of \$$current_price.", "closed");
				$x++;
			}

		}
		mysql_free_result ($result);
	}

	// Get all "outbid" notifications
	$result = $dbinfo->query ("select * from bids_on
where 	username = '$username'
AND 	(item_end_day > $curr_day
	OR	(item_end_day = $curr_day AND item_end_hour > $curr_hour)
	OR	(item_end_day = $curr_day AND item_end_hour = $curr_hour AND item_end_minute > $curr_minute)
	)
AND 	display_notification = 'y'
order by bid_day desc, bid_hour desc, bid_minute desc");
	while (list ($user, $title, $seller, $category, $end_day, $end_hour, $end_min,
		     $bid_day, $bid_hour, $bid_min, $bid_amt)
	       = mysql_fetch_row ($result))
	{
		$real_title = href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title);
		// Find the person that out bid "you"
		$result2 = $dbinfo->query ("select username, bid_amount, bid_day, bid_hour, bid_minute from bids_on
where 	item_title = '$title'
AND 	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_min
AND	bid_amount > $bid_amt
order by bid_amount");
		list ($outbid_user, $outbid_amt, $outbid_day, $outbid_hour, $outbid_min)
			= mysql_fetch_row ($result2);
		mysql_free_result ($result2);
		// Find the current highest bidder for the item.
		$result2 = $dbinfo->query ("select username, bid_amount, bid_day, bid_hour, bid_minute from bids_on
where 	item_title = '$title'
AND 	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_min
order by bid_amount desc");
		list ($highest_user, $highest_amt, $highest_day, $highest_hour, $highest_min)
			= mysql_fetch_row ($result2);
		mysql_free_result ($result2);
		$outbid_realname = href ("profile.php?mode=view&username=$outbid_user", $dbinfo->get_realname ($outbid_user));
		$highest_realname = href ("profile.php?mode=view&username=$highest_user", $dbinfo->get_realname ($highest_user));
		$seller_realname = href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller));
		$bid_diff = $outbid_amt - $bid_amt;
		$highest_bid_diff = $highest_amt - $bid_amt;
		$output[$x] = pad_time ($outbid_day, $outbid_hour, $outbid_min, 6).div (div (href ("$current_script?mode=remove&t=$title&s=$seller&c=$category&d=$end_day&h=$end_hour&m=$end_min&bd=$bid_day&bh=$bid_hour&bm=$bid_min", "X"), "outbid_hide").
			  span (format_time ($outbid_day, $outbid_hour, $outbid_min), "time").
			  "$outbid_realname outbid you by \$$bid_diff on $seller_realname's auction of \"$real_title\" with a bid of \$$outbid_amt.<br/>".
			  span (format_time ($highest_day, $highest_hour, $highest_min), "time2")."$highest_realname became the highest bidder (\$$highest_bid_diff over your bid) with a bid of \$$highest_amt.<br/>".
			  span (format_time ($end_day, $end_hour, $end_min), "time2")." The auction will end.", "outbid");
		$x++;
	}
	mysql_free_result ($result);

	// Get user activity
	$result = $dbinfo->query ("select day, hour, minute, activity from user_activity
where username = '$username'
AND activity != 'Current Time'
order by day desc, hour desc, minute desc");
	while (list ($d, $h, $m, $a) = mysql_fetch_row ($result))
	{
		$output[$x] = pad_time ($d, $h, $m, 3).div (span (format_time ($d, $h, $m), "time")."$a", "useractivity");
		$x++;
	}
	mysql_free_result ($result);


	// Actual output of all notifications
	cout ("Welcome to your home page.");
	cout ("");
	cout ("Activity and notifications:");
	rsort ($output);
	for ($i = 0; $i < count ($output); $i++)
	{
		echo substr ($output[$i], strpos ($output[$i], " "), strlen ($output[$i]));
	}
}
else
{
	$loginlink = href ("login.php", "Login");
	$reglink= href ("registration.php", "Register");
	echo <<<HEREDOC
Welcome to CMPS460 Group I's eBay-ish website "iBay"! You will need to $loginlink or $reglink to proceed further.
HEREDOC;
	echo_div("divname");
	echo local_img("gavel.jpg');
	end_div();
}

echo_footer ($dbinfo);

?>
