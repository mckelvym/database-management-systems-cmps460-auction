<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$view = get ("view");
$sortby = get ("sortby");

if (!$dbinfo->logged_in ())
	redirect ("index.php");

if ($dbinfo->logged_in ())
{
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
	
	else
	{

		echo h3 ("$view");
		echo_div ("scriptstatus");
		echo href (" itemlisting.php?mode=new", "Create a New Item Listing");
		end_div ();

		echo "<a href=category.php?view=$view&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=$view&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=$view&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=$view&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";	
	

	    if ($sortby == "time_remaining")
	    {
		    $query = "select title, seller, end_day, end_hour, end_minute,
                     current_price from item_listing where category =
                     '$view' AND end_day >= '$day' AND end_hour >=
                     '$hour' AND end_minute > '$minute' order by end_day
                     desc, end_hour desc, end_minute desc";
	    }

	    else if ($sortby == "title")
	    {
		    $query = "select title, seller, end_day, end_hour, end_minute,
                     current_price from item_listing where category =
                     '$view' AND end_day >= '$day' AND end_hour >=
                     '$hour' AND end_minute > '$minute' order by title";
	    }

	    else if ($sortby == "seller")
	    {
		    $query = "select title, seller, end_day, end_hour, end_minute,
                     current_price from item_listing where category =
                     '$view' AND end_day >= '$day' AND end_hour >=
                     '$hour' AND end_minute > '$minute' order by seller";
	    }

	    else if ($sortby == "current_bid")
	    {
		    $query = "select title, seller, end_day, end_hour, end_minute,
                     current_price from item_listing where category =
                     '$view' AND end_day >= '$day' AND end_hour >=
                     '$hour' AND end_minute > '$minute' order by
                     current_price";
	    }

	    else
	    {
		    $query = "select title, seller, end_day, end_hour, end_minute,
                     current_price from item_listing where category =
                     '$view' AND end_day >= '$day' AND end_hour >=
                     '$hour' AND end_minute > '$minute'";

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
                $table->init ("tbl_std");

                echo $table->table_begin ();
                echo $table->table_head_begin ();
                echo $table->tr ($table->td_span ("Available for Bid", "", 6));
                echo $table->tr ($table->td ("Title").
			         $table->td ("Seller").
			         $table->td ("Time").
			         $table->td ("Current Price"));
                echo $table->tr_end ();
                echo $table->table_head_end ();
                
                echo $table->table_body_begin ();
                while (list($title, $seller, $end_day, $end_hour,$end_min,
		            $curr_price) = mysql_fetch_row($results_id))
			    {
                    echo $table->tr ($table->td (href ("itemlisting.php?mode=view&title=$title&seller=$seller&category=$view&end_day=$end_day&end_hour=$end_hour&end_minute=$end_min", $title)).
		                             $table->td (href ("profile.php?mode=view&username=$seller", $seller)).
		                             $table->td (format_time ($end_day,
		                                         $end_hour, $end_minute)).
					                 $table->td ($curr_price));
				        
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
