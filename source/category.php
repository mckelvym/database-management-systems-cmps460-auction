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

// Handles category views, showing listings in a specific category and sorting.




include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$view = get ("view");
$sortby = get ("sortby");

// Redirect if user not logged in
if (!$dbinfo->logged_in ())
	redirect ("index.php");

if ($dbinfo->logged_in ())
{
	// Default view shows user a dropdown
	if (empty ($view))
	{
		echo h3 ("All Categories");
		echo_div ("scriptstatus");
		echo href (" itemlisting.php?mode=new", "Create a New Item Listing");
		end_div ();

		cout ("Select a category:");
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
		echo form_begin ("$current_script?", "get");
		echo select ("view", $options);
		echo submit_input ("Select");

	}
	else // show category specific listings
	{

		echo h3 ("$view");
		echo_div ("scriptstatus");
		echo href ("itemlisting.php?mode=new", "Create a New Item Listing");
		echo "<br/>Sort by: ";
		echo href ("category.php?view=$view&sortby=time_remaining", "Time Remaining");
		echo " | ";
		echo href ("category.php?view=$view&sortby=title", "Title");
		echo " | ";
		echo href ("category.php?view=$view&sortby=seller", "Seller");
		echo " | ";
		echo href ("category.php?view=$view&sortby=current_bid", "Current Bid (Lowest at Top)");
		end_div ();

		$day = $dbinfo->day ();
		$hour = $dbinfo->hour ();
		$minute = $dbinfo->minute ();
		$query = "select title, seller, end_day, end_hour, end_minute, current_price from item_listing
where	category = '$view'
AND	((end_day > $day)
	OR (end_day = $day AND end_hour > $hour)
	OR (end_day = $day AND end_hour = $hour AND end_minute >= $minute))";

		if ($sortby == "title")
		{
			$query = $query." order by title";
		}
		else if ($sortby == "seller")
		{
			$query = $query." order by seller";
		}

		else if ($sortby == "current_bid")
		{
			$query = $query." order by current_price";
		}
		else // ($sortby == "time_remaining")
		{
			$query = $query." order by end_day,
end_hour, end_minute";
		}

		// Run the query
		$results_id = mysql_query($query);
		if($results_id)
		{
			if (mysql_num_rows($results_id) == 0)
			{
				echo "No data.";
			}
			else
			{
				$table = new table_common_t ();
				$table->init ("category_listing");

				echo $table->table_begin ();
				echo $table->table_head_begin ();
				echo $table->tr ($table->td_span ("Auctions available for bid in \"$view\"", "", 6));
				echo $table->tr ($table->td ("Title").
						 $table->td ("Seller").
						 $table->td ("Closing Time").
						 $table->td ("Current Price"));
				echo $table->tr_end ();
				echo $table->table_head_end ();

				echo $table->table_body_begin ();
				while (list($title, $seller, $end_day, $end_hour,$end_min,
					    $curr_price) = mysql_fetch_row($results_id))
				{
					echo $table->tr ($table->td (href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$view&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title)).
							 $table->td (href ("profile.php?mode=view&username=$seller", $dbinfo->get_realname ($seller))).
							 $table->td (format_time ($end_day,
										  $end_hour, $end_min)).
					                 $table->td ("\$$curr_price"));

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


}

echo_footer ($dbinfo);

?>
