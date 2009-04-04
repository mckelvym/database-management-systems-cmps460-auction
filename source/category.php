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
	else if ($view == "clothes")
	{
		print '<font size="5" color="blue">';
		print "Clothes</font><br><br>";

		echo "<a href=category.php?view=clothes&sortby=time_remaining>
		        Sort by Time Remaining</a> |
		      <a href=category.php?view=clothes&sortby=title>
		        Sort by Title</a> |
		      <a href=category.php?view=clothes&sortby=seller>
		        Sort by Seller</a> |
		      <a href=category.php?view=clothes&sortby=current_bid>
		        Sort by Current Bid (Lowest Bid at Top)</a><br>";
		$category = "Clothes";
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
                 current_bid";
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
				echo "<pre>";
				echo "Title:           $title<br>";
				echo "Seller:          $seller<br>";
				echo "End Day:         $end_day<br>";
				echo "End Hour:        $end_hour<br>";
				echo "End Minute:      $end_min<br>";
				echo "Current Price:   $curr_price<br>";
				echo "</pre>";
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
