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
		cout ("Select a category:");
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
		echo form_begin ("$current_script?", "post");
		echo select ("view", $options);
		echo submit_input ("Select");
	}

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
		$category = "Art";
	}
	else if ($view == "books")
	{
		print '<font size="5" color="blue">';
		print "Books</font><br><br>";

		echo "<a href=category.php?view=books&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=books&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=books&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=books&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Books";
	}
	else if ($view == "clothing")
	{
		print '<font size="5" color="blue">';
		print "Clothing</font><br><br>";

		echo "<a href=category.php?view=clothes&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=clothes&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=clothes&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=clothes&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Clothing";
	}
	else if ($view == "collectibles")
	{
		print '<font size="5" color="blue">';
		print "Collectibles</font><br><br>";

		echo "<a href=category.php?view=collectibles&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=collectibles&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=collectibles&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=collectibles&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Collectibles";
	}
	else if ($view == "electronics")
	{
		print '<font size="5" color="blue">';
		print "Electronics</font><br><br>";

		echo "<a href=category.php?view=electronics&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=electronics&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=electronics&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=electronics&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Electronics";
	}
	else if ($view == "entertainment")
	{
		print '<font size="5" color="blue">';
		print "Entertainment</font><br><br>";

		echo "<a href=category.php?view=entertainment&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=entertainment&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=entertainment&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=entertainment&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Entertainment";
	}
	else if ($view == "jewelry")
	{
		print '<font size="5" color="blue">';
		print "Jewelry</font><br><br>";

		echo "<a href=category.php?view=jewelry&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=jewelry&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=jewelry&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=jewelry&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Jewelry";
	}
	else if ($view == "sporting_goods")
	{
		print '<font size="5" color="blue">';
		print "Sporting Goods</font><br><br>";

		echo "<a href=category.php?view=sporting_goods&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=sporting_goods&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=sporting_goods&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=sporting_goods&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Sporting Goods";
	}
	else if ($view == "toys")
	{
		print '<font size="5" color="blue">';
		print "Toys</font><br><br>";

		echo "<a href=category.php?view=toys&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=toys&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=toys&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=toys&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Toys";
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
	}

	// Select the database

	if ($sortby == "time_remaining")
	{
		$query = "select title, seller, end_day, end_hour, end_minute,
                 current_price from item_listing where category =
                 '$category' AND end_day >= '$day' AND end_hour >=
                 '$hour' AND end_minute > '$minute' order by end_day
                 desc, end_hour desc, end_minute desc";
	}

	else if ($sortby == "title")
	{
		$query = "select title, seller, end_day, end_hour, end_minute,
                 current_price from item_listing where category =
                 '$category' AND end_day >= '$day' AND end_hour >=
                 '$hour' AND end_minute > '$minute' order by title";
	}

	else if ($sortby == "seller")
	{
		$query = "select title, seller, end_day, end_hour, end_minute,
                 current_price from item_listing where category =
                 '$category' AND end_day >= '$day' AND end_hour >=
                 '$hour' AND end_minute > '$minute' order by seller";
	}

	else if ($sortby == "current_bid")
	{
		$query = "select title, seller, end_day, end_hour, end_minute,
                 current_price from item_listing where category =
                 '$category' AND end_day >= '$day' AND end_hour >=
                 '$hour' AND end_minute > '$minute' order by
                 current_price";
	}

	else
	{
		if (empty($view))
			$query = "select title, seller, end_day, end_hour, end_minute,
                  current_price from item_listing";
		else
			$query = "select title, seller, end_day, end_hour, end_minute,
                  current_price from item_listing where category =
                  '$category'";

	}
	// Run the query
	$results_id = mysql_query($query);
	if($results_id)
	{
		if (mysql_num_rows($results_id) == 0){
			echo "No data.";
		}
		else{
			// Get each row of the result, assign a variable to each
			// attribute in the row, and echo the data with labels
			while (list($title, $seller, $end_day, $end_hour,$end_min,
				    $curr_price) = mysql_fetch_row($results_id))
			{
    		    // Start writing table to page
	            $table = new table_common_t ();
	            $table->init ("tbl_std");

	            echo $table->table_begin ();
	            echo $table->table_head_begin ();
		        echo $table->tr ($table->td_span ("Available for Bid", "", 6));
	            echo $table->tr_end ();
	            echo $table->table_head_end ();
	            
	            echo $table->table_body_begin ();				     
		        echo $table->tr ($table->td ("Title").
		                         $table->td ($title));
				echo $table->tr ($table->td ("Seller").
		                         $table->td ($seller));		 
				echo $table->tr ($table->td ("End Day").
		                         $table->td ($end_day));
				echo $table->tr ($table->td ("End Hour").
		                         $table->td ($end_hour));				 
				echo $table->tr ($table->td ("End Minute").
		                         $table->td ($end_min));					 
				echo $table->tr ($table->td ("Current Price").
		                         $table->td ($curr_price));	
				echo $table->table_body_end ();
				
	            echo $table->table_end ();
	            echo "<br><br>";
			}
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
