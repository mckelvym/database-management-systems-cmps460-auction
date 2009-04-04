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
	if ($view == "art")
	{
		print '<font size="5" color="blue">';
		print "Art</font><br><br>";
		
		echo "<a href=category.php?view=art&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=art&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=art&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=art&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		        
		// Select the database
		        
		if ($sortby == "time_remaining")
		{
            $query="select * from item_listing where category = Art order by
                    end_day desc, end_hour desc, end_minute desc";
    	}
    	
		else if ($sortby == "title")
		{
            $query="select * from item_listing where category = Art order by
                    title";
    	}
    	
		else if ($sortby == "seller")
		{
            $query="select * from item_listing where category = Art order by
                    seller";
    	}
    	
		else if ($sortby == "current_bid")
		{
            $query="select * from item_listing where category = Art order by
                    current_price";
    	}	
		
		else
		{
            $query="select * from item_listing where category = Art";
        }
    }
    
	else
	{
		print '<font size="5" color="blue">';
		print "All Categories</font><br><br>";
		
		echo "<a href=category.php?sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		        
		// Select the database
		        
		if ($sortby == "time_remaining")
		{
            $query="select * from item_listing order by end_day desc,
                    end_hour desc, end_minute desc";
    	}
    	
		else if ($sortby == "title")
		{
            $query="select * from item_listing order by title";
    	}
    	
		else if ($sortby == "seller")
		{
            $query="select * from item_listing order by seller";
    	}
    	
		else if ($sortby == "current_bid")
		{
            $query="select * from item_listing order by current_price";
    	}	
		
		else
		{
            $query="select * from item_listing";
        }
    }

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
			echo "<pre>";
			echo "Title:           $title<br>";
			echo "Seller:          $seller<br>";
			echo "Category:        $category<br>";
			echo "End Day:         $end_day<br>";
			echo "End Hour:        $end_hour<br>";
			echo "End Minute:      $end_min<br>";
			echo "Description:     $descr<br>";
			echo "Shipping Cost:   $ship_cost<br>";
			echo "Shipping Method: $ship_meth<br>";
			echo "Starting Price:  $str_price<br>";
			echo "Current Price:   $curr_price<br>";
			echo "Picture:         $pict<br>";
			echo "Buyer:           $buyer<br>";
			echo "Buyer Feedback Description:  $buy_fdbk_descr<br>";
			echo "Buyer Feedback Rating:       $buy_fdbk_rate<br>";
			echo "Seller Feedback Description: $sell_fdbk_descr<br><br>";
			echo "</pre>";
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

echo_footer ($dbinfo);

?>
