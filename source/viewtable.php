<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

$table = get ("name");
$order = get("order");
$desc = get("desc");

if ($dbinfo->logged_in () && $dbinfo->is_admin ())
{
	if ($table == "user")
	{
		print '<font size="5" color="blue">';
		print "User Data</font><br><br></font>";


		echo "<a href=viewtable.php?name=user&order=username&desc=0>
		        Sort by Username Ascending</a> |
		      <a href=viewtable.php?name=user&order=username&desc=1>
		        Sort by Username Descending</a><br>";

		// Select the database

		if ($order == "username")
		{
            if ($desc == "0")
            {
                $query="select * from $table order by username";
            }

            else if ($desc == "1")
            {
                $query="select * from $table order by username desc";
            }
            else if ($desc != "0"|"1")
            {
                $query="select * from $table order by username";
            }
		}

		else $query="select * from $table";

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
	            $table->init ("tbl_std");

	            echo $table->table_begin ();
	            echo $table->table_head_begin ();
		        echo $table->tr ($table->td_span ("Registered User of iBay", "", 6));
	            echo $table->tr_end ();
	            echo $table->table_head_end ();

	            echo $table->table_body_begin ();
		        echo $table->tr ($table->td ("Username").
		                         $table->td ($username));
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
		                         $table->td ($picture));
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

	else if ($table == "user_activity")
	{
		print '<font size="5" color="blue">';
		print "Activity Data</font><br><br>";

		// Select the database
		$query="select * from $table order by day desc, hour desc, minute desc";

		// Run the query
		$results_id = mysql_query($query);
		if($results_id)
		{
			// Get each row of the result, assign a variable to each
			// attribute in the row, and echo the data with labels
			while (list($username, $day, $hour, $minute, $activity)
			       = mysql_fetch_row($results_id))
			{
    		    // Start writing table to page
	            $table = new table_common_t ();
	            $table->init ("tbl_std");

	            echo $table->table_begin ();
	            echo $table->table_head_begin ();
		        echo $table->tr ($table->td_span ("Activity Log Entry", "", 6));
	            echo $table->tr_end ();
	            echo $table->table_head_end ();

	            echo $table->table_body_begin ();
		        echo $table->tr ($table->td ("Username").
		                         $table->td ($username));
				echo $table->tr ($table->td ("Day").
		                         $table->td ($day));
				echo $table->tr ($table->td ("Hour").
		                         $table->td ($hour));
				echo $table->tr ($table->td ("Minute").
		                         $table->td ($minute));
				echo $table->tr ($table->td ("Activity").
		                         $table->td ($activity));
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

	else if ($table == "item_listing")
	{
		print '<font size="5" color="blue">';
		print "Item Listing Data</font><br><br>";

		echo "<a href=viewtable.php?name=item_listing&order=seller&desc=0>
		        Sort by Seller Ascending</a> |
		      <a href=viewtable.php?name=item_listing&order=seller&desc=1>
		        Sort by Seller Descending</a><br>";

		// Select the database

		if ($order == "seller")
		{
            if ($desc == "0")
            {
                $query="select * from $table order by seller";
            }

            else if ($desc == "1")
            {
                $query="select * from $table order by seller desc";
            }
            else if ($desc != "0"|"1")
            {
                $query="select * from $table order by seller";
            }
		}

		else $query="select * from $table";

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
	            $table->init ("tbl_std");

	            echo $table->table_begin ();
	            echo $table->table_head_begin ();
		        echo $table->tr ($table->td_span ("Auction Item Listing", "", 6));
	            echo $table->tr_end ();
	            echo $table->table_head_end ();

	            echo $table->table_body_begin ();
		        echo $table->tr ($table->td ("Title").
		                         $table->td (href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$category&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title)));
				echo $table->tr ($table->td ("Seller").
		                         $table->td ($seller));
				echo $table->tr ($table->td ("Category").
		                         $table->td ($category));
				echo $table->tr ($table->td ("End Day").
		                         $table->td ($end_day));
				echo $table->tr ($table->td ("End Hour").
		                         $table->td ($end_hour));
				echo $table->tr ($table->td ("End Minute").
		                         $table->td ($end_min));
				echo $table->tr ($table->td ("Description").
		                         $table->td ($descr));
				echo $table->tr ($table->td ("Shipping Cost").
		                         $table->td ($ship_cost));
				echo $table->tr ($table->td ("Shipping Method").
		                         $table->td ($ship_meth));
				echo $table->tr ($table->td ("Starting Price").
		                         $table->td ($str_price));
				echo $table->tr ($table->td ("Current Price").
		                         $table->td ($curr_price));
				echo $table->tr ($table->td ("Picture").
		                         $table->td ($pict));
				echo $table->tr ($table->td ("Buyer").
		                         $table->td ($buyer));
				echo $table->tr ($table->td ("Buyer Feedback Description").
		                         $table->td ($buy_fdbk_descr));
				echo $table->tr ($table->td ("Buyer Feedback Rating").
		                         $table->td ($buy_fdbk_rate));
				echo $table->tr ($table->td ("Seller Feedback Description").
		                         $table->td ($sell_fdbk_descr));
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

	else if ($table == "bids_on")
	{
		print '<font size="5" color="blue">';
		print "Bid Data</font><br><br>";

		echo "<a href=viewtable.php?name=bids_on&order=username&desc=0>
		        Sort by Username Ascending</a> |
		      <a href=viewtable.php?name=bids_on&order=username&desc=1>
		        Sort by Username Descending</a><br>";

		// Select the database

		if ($order == "username")
		{
            if ($desc == "0")
            {
                $query="select * from $table order by username";
            }

            else if ($desc == "1")
            {
                $query="select * from $table order by username desc";
            }
            else if ($desc != "0"|"1")
            {
                $query="select * from $table order by username";
            }
		}

		else $query="select * from $table";

		// Run the query
		$results_id = mysql_query($query);
		if($results_id)
		{
			// Get each row of the result, assign a variable to each
			// attribute in the row, and echo the data with labels
			while (list($username, $title, $seller, $category, $end_day,
				    $end_hour, $end_min, $bid_day, $bid_hour, $bid_min,
				    $bid_amt, $disp_notif)
			       = mysql_fetch_row($results_id))
			{
    		    // Start writing table to page
	            $table = new table_common_t ();
	            $table->init ("tbl_std");

	            echo $table->table_begin ();
	            echo $table->table_head_begin ();
		        echo $table->tr ($table->td_span ("Bid Information", "", 6));
	            echo $table->tr_end ();
	            echo $table->table_head_end ();

	            echo $table->table_body_begin ();
		        echo $table->tr ($table->td ("Username").
		                         $table->td ($username));
		        echo $table->tr ($table->td ("Title").
		                         $table->td ($title));
				echo $table->tr ($table->td ("Seller").
		                         $table->td ($seller));
				echo $table->tr ($table->td ("Category").
		                         $table->td ($category));
				echo $table->tr ($table->td ("End Day").
		                         $table->td ($end_day));
				echo $table->tr ($table->td ("End Hour").
		                         $table->td ($end_hour));
				echo $table->tr ($table->td ("End Minute").
		                         $table->td ($end_min));
				echo $table->tr ($table->td ("Bid Day").
		                         $table->td ($bid_day));
				echo $table->tr ($table->td ("Bid Hour").
		                         $table->td ($bid_hour));
				echo $table->tr ($table->td ("Bid Minute").
		                         $table->td ($bid_min));
				echo $table->tr ($table->td ("Bid Amount").
		                         $table->td ($bid_amt));
				echo $table->tr ($table->td ("Display Notification").
		                         $table->td ($disp_notif));
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

else if ($dbinfo->logged_in ())
{
	redirect ("index.php");
}

else
{
	redirect ("index.php");
}

echo_footer ($dbinfo);

?>
