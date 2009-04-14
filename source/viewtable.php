<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

$mode = get ("mode");
$name = get ("name");
$order = get("order");
$desc = get("desc");
$current_script = current_script ();

function display_message ()
{
	$message = get ("msg");
	echo "Messages:<br/>";
	echo ul (li ($message));
}


if ($dbinfo->logged_in () && $dbinfo->is_admin ())
{
	if ($name == "user")
	{
		echo h3 ("User Data");
		echo_div ("scriptstatus");
		echo href ("$current_script?name=user&order=username&desc=0", "Sort Ascending (Usernames)");
		echo " | ";
		echo href ("$current_script?name=user&order=username&desc=1", "Sort Descending (Usernames)");
		end_div ();

		// Select the database

		if ($order == "username")
		{
			if ($desc == "0")
			{
				$query="select * from $name order by username";
			}

			else if ($desc == "1")
			{
				$query="select * from $name order by username desc";
			}
			else if ($desc != "0"|"1")
			{
				$query="select * from $name order by username";
			}
		}

		else $query="select * from $name";

		// Run the query
		$results_id = mysql_query($query);
		if($results_id)
		{
			// Get each row of the result, assign a variable to each
			// attribute in the row, and echo the data with labels
			while (list($username, $passwd, $is_admin, $name, $dob, $street,
				    $city, $state, $zip, $phone, $email, $cc, $ccn, $ccx,
				    $picture, $descr)
			       = mysql_fetch_row($results_id))
			{
				// Start writing table to page
				$table = new table_common_t ();
				$table->init ("user_database");

				echo $table->table_begin ();
				echo $table->table_head_begin ();
				echo $table->tr ($table->td_span ("Registered User of iBay", "", 6));
				echo $table->tr_end ();
				echo $table->table_head_end ();

				echo $table->table_body_begin ();
				echo $table->tr ($table->td ("Username").
						 $table->td ($username."<br/>".href ("profile.php?mode=view&username=$username", "Profile")));
				echo $table->tr ($table->td ("Password").
						 $table->td ($passwd));
				echo $table->tr ($table->td ("Admin?").
						 $table->td ($is_admin));
				echo $table->tr ($table->td ("Name").
						 $table->td ($name));
				echo $table->tr ($table->td ("D.O.B.").
						 $table->td ($dob));
				echo $table->tr ($table->td ("Street").
						 $table->td ($street));
				echo $table->tr ($table->td ("City").
						 $table->td ($city));
				echo $table->tr ($table->td ("State").
						 $table->td ($state));
				echo $table->tr ($table->td ("Zip").
						 $table->td ($zip));
				echo $table->tr ($table->td ("Phone").
						 $table->td ($phone));
				echo $table->tr ($table->td ("Email").
						 $table->td ($email));
				echo $table->tr ($table->td ("Credit Card").
						 $table->td ($cc));
				echo $table->tr ($table->td ("CC Number").
						 $table->td ($ccn));
				echo $table->tr ($table->td ("CC Exp.Date").
						 $table->td ($ccx));
				echo $table->tr ($table->td ("Picture").
						 $table->td (local_img ($picture)));
				echo $table->tr ($table->td ("Description").
						 $table->td ($descr));
				echo $table->table_body_end ();

				echo $table->table_end ();
				echo "<br><br>";
			}
		}
		else if ($dbinfo->debug ())
		{
			// Display the query and the MySQL error message
			print "<br><br>QUERY FAILED !!! <br><br>QUERY = $query <br>
                  <br>ERROR = ";
			die (mysql_error());
		}
	}

	else if ($name == "user_activity")
	{
		echo h3 ("User Activity Data");
		echo_div ("scriptstatus");
		echo href ("$current_script?name=user_activity&order=username", "Sort by username");
		echo " | ";
		echo href ("$current_script?name=user_activity&order=time", "Sort by time");
		end_div ();

		// Select the database
		if (empty ($order) || $order == "time")
			$query="select * from $name order by day desc, hour desc, minute desc";
		else
			$query="select * from $name order by username";

		// Run the query
		$results_id = mysql_query($query);
		if($results_id)
		{
			// Get each row of the result, assign a variable to each
			// Start writing table to page
			$table = new table_common_t ();
			$table->init ("user_activity_database");

			echo $table->table_begin ();
			echo $table->table_head_begin ();
			echo $table->tr ($table->td_span ("Activity Log Entry", "", 3));
			echo $table->tr ($table->td ("Username").
					 $table->td ("Time").
					 $table->td ("Activity"));
			echo $table->table_head_end ();
			echo $table->table_body_begin ();

			// attribute in the row, and echo the data with labels
			while (list($username, $day, $hour, $minute, $activity)
			       = mysql_fetch_row($results_id))
			{
				echo $table->tr (
					$table->td (href ("profile.php?mode=view&username=$username", $username)).
					$table->td (format_time ($day, $hour, $minute)).
					$table->td ($activity));
			}
			echo $table->table_body_end ();
		}
		else if ($dbinfo->debug ())
		{
			// Display the query and the MySQL error message
			print "<br><br>QUERY FAILED !!! <br><br>QUERY = $query <br>
                  <br>ERROR = ";
			die (mysql_error());
		}
	}

	else if ($name == "item_listing")
	{
		echo h3 ("Item Listing Data");
		echo_div ("scriptstatus");
		echo href ("$current_script?name=item_listing&order=seller&desc=0", "Sort by seller (ascending)");
		echo " | ";
		echo href ("$current_script?name=item_listing&order=seller&desc=1", "Sort by seller (descending)");
		end_div ();

		// Select the database

		if ($order == "seller")
		{
			if ($desc == "0")
			{
				$query="select * from $name order by seller";
			}

			else if ($desc == "1")
			{
				$query="select * from $name order by seller desc";
			}
			else if ($desc != "0"|"1")
			{
				$query="select * from $name order by seller";
			}
		}

		else $query="select * from $name";

		// Run the query
		$results_id = mysql_query($query);
		if($results_id)
		{
			// Get each row of the result, assign a variable to each
			// attribute in the row, and echo the data with labels
			while (list($title, $seller, $category, $end_day, $end_hour,
				    $end_min, $descr, $ship_cost, $ship_meth, $str_price,
				    $curr_price, $pict, $buyer, $buy_fdbk_descr,
				    $buy_fdbk_rate, $sell_fdbk_descr)
			       = mysql_fetch_row($results_id))
			{
				// Start writing table to page
				$table = new table_common_t ();
				$table->init ("item_listing_database");

				echo $table->table_begin ();
				echo $table->table_head_begin ();
				echo $table->tr ($table->td_span ("Auction Item Listing", "", 6));
				echo $table->tr_end ();
				echo $table->table_head_end ();

				echo $table->table_body_begin ();
				echo $table->tr ($table->td ("Title").
						 $table->td (href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title)));
				echo $table->tr ($table->td ("Seller").
						 $table->td (href ("profile.php?mode=view&username=$seller", $seller)));
				echo $table->tr ($table->td ("Category").
						 $table->td (href ("category.php?view=".str_replace (" ", "_", strtolower ($category)), $category)));
				echo $table->tr ($table->td ("End Time").
						 $table->td (format_time ($end_day, $end_hour, $end_min)));
				echo $table->tr ($table->td ("Description").
						 $table->td ($descr));
				echo $table->tr ($table->td ("Shipping Cost").
						 $table->td ("\$$ship_cost"));
				echo $table->tr ($table->td ("Shipping Method").
						 $table->td ($ship_meth));
				echo $table->tr ($table->td ("Starting Price").
						 $table->td ("\$$str_price"));
				echo $table->tr ($table->td ("Current Price").
						 $table->td ("\$$curr_price"));
				echo $table->tr ($table->td ("Picture").
						 $table->td (local_img ($pict)));
				echo $table->tr ($table->td ("Buyer").
						 $table->td ($buyer));
				if ($buy_fdbk_rate != -1)
				{
					echo $table->tr ($table->td ("Buyer Feedback Description").
							 $table->td ($buy_fdbk_descr));
					echo $table->tr ($table->td ("Buyer Feedback Rating").
							 $table->td ($buy_fdbk_rate));
				}
				else
				{
					echo $table->tr ($table->td ("Buyer Feedback Description").
							 $table->td ("None."));
				}

				if (!empty ($sell_fdbk_descr))
				{
					echo $table->tr ($table->td ("Seller Feedback Description").
							 $table->td ($sell_fdbk_descr));
				}
				else
				{
					echo $table->tr ($table->td ("Seller Feedback Description").
							 $table->td ("None."));
				}
				echo $table->table_body_end ();

				echo $table->table_end ();
				echo "<br><br>";
			}
		}
		else if ($dbinfo->debug ())
		{
			// Display the query and the MySQL error message
			print "<br><br>QUERY FAILED !!! <br><br>QUERY = $query <br>
                  <br>ERROR = ";
			die (mysql_error());
		}
	}

	else if ($name == "bids_on")
	{
		if ($mode == "delete")
		{
			$user = get ("username");
			$title = get ("title");
			$seller = get ("seller");
			$category = get ("category");
			$end_day = get ("end_day");
			$end_hour = get ("end_hour");
			$end_minute = get ("end_minute");
			$bid_day = get ("bid_day");
			$bid_hour = get ("bid_hour");
			$bid_minute = get ("bid_minute");
			$dbinfo->query ("delete from bids_on
where	username = '$user'
AND	item_title = '$title'
AND	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_minute
AND	bid_day = $bid_day
AND	bid_hour = $bid_hour
AND	bid_minute = $bid_minute");
		}

		echo h3 ("Bidding Data");
		echo_div ("scriptstatus");
		echo href ("$current_script?name=bids_on&order=username&desc=0", "Sort by username (ascending)");
		echo " | ";
		echo href ("$current_script?name=bids_on&order=username&desc=1", "Sort by username (descending)");
		end_div ();

		// Select the database
		if ($order == "username")
		{
			if ($desc == "0")
			{
				$query="select * from $name order by username";
			}

			else if ($desc == "1")
			{
				$query="select * from $name order by username desc";
			}
			else if ($desc != "0"|"1")
			{
				$query="select * from $name order by username";
			}
		}

		else $query="select * from $name";

		// Run the query
		$results_id = mysql_query($query);
		if($results_id)
		{
			$table = new table_common_t ();
			$table->init ("bidding_database");

			echo $table->table_begin ();
			echo $table->table_head_begin ();
			echo $table->tr ($table->td_span ("Bid Information", "", 8));
			echo $table->tr ($table->td ("Bidder").
					 $table->td ("Title / Category").
					 $table->td ("Seller").
					 $table->td ("End Time / Bid Time").
					 $table->td ("Amount").
					 $table->td ("Notify").
					 $table->td ("Modify"));
			echo $table->table_head_end ();
			echo $table->table_body_begin ();

			// Get each row of the result, assign a variable to each
			// attribute in the row, and echo the data with labels
			while (list($username, $title, $seller, $category, $end_day,
				    $end_hour, $end_min, $bid_day, $bid_hour, $bid_min,
				    $bid_amt, $disp_notif)
			       = mysql_fetch_row($results_id))
			{
				if ($disp_notif != "n")
				{
					$can_delete = $table->td (href ("$current_script?name=$name&order=$order&desc=$desc&mode=delete&username=$username&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min&bid_day=$bid_day&bid_hour=$bid_hour&bid_minute=$bid_min", "X"));
				}
				else
				{
					$can_delete = $table->td ("-");
				}

				// Start writing table to page
				echo $table->tr ($table->td (href ("profile.php?mode=view&username=$username", $dbinfo->get_realname ($username))).
						 $table->td (href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title).
							     "<br/>(".
							     href ("category.php?view=".str_replace (" ", "_", strtolower ($category)), $category).")").
						 $table->td (href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller))).
						 $table->td (format_time ($end_day, $end_hour, $end_min).
							     "/<br/>".
							     format_time ($bid_day, $bid_hour, $bid_min)).
						 $table->td ("\$$bid_amt").
						 $table->td ($disp_notif).
						 $can_delete);
			}
			echo $table->table_body_end ();

			echo $table->table_end ();
			echo "<br><br>";
		}
		else if ($dbinfo->debug ())
		{
			// Display the query and the MySQL error message
			print "<br><br>QUERY FAILED !!! <br><br>QUERY = $query <br>
                  <br>ERROR = ";
			die (mysql_error());
		}
	}
	else
	{
		echo "Errors were detected:<br/>";
		echo ul (li ("Invalid table name '$name'."));
		cout ("Back to your ".href ("index.php", "home").".");
	}
}

else
{
	redirect ("index.php");
}

echo_footer ($dbinfo);

?>

