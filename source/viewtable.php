<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

$table = get ("name");
if ($dbinfo->logged_in () && $dbinfo->is_admin ())
{
	if ($table == "user")
	{
        print '<font size="5" color="blue">';
        print "User Data</font><br><br>";

        // Select the database
        $query="select * from $table";

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
                echo "<pre>";
                echo "Username:    $username<br>";
                echo "Password:    $passwd<br>";
                echo "Is_Admin:    $is_admin<br>";
                echo "Name:        $name<br>";
                echo "D.O.B.:      $dob<br>";
                echo "Street:     $street<br>";
                echo "City:        $city<br>";
                echo "State:       $state<br>";
                echo "Zip:         $zip<br>";
                echo "Phone:       $phone<br>";
                echo "Email:       $email<br>";
                echo "Credit Card: $cc<br>";
                echo "CC Number:   $ccn<br>";
                echo "CC Exp.Date: $ccx<br>";
                echo "Picture:     $picture<br>";
                echo "Description: $descr<br><br>";
                echo "</pre>";
           }
        }
        else
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
        $query="select * from $table";

        // Run the query
        $results_id = mysql_query($query);
        if($results_id)
        {
           // Get each row of the result, assign a variable to each
           // attribute in the row, and echo the data with labels
           while (list($username, $day, $hour, $minute, $activity)
                 = mysql_fetch_row($results_id))
           {
                echo "<pre>";
                echo "Username:  $username<br>";
                echo "Day:       $day<br>";
                echo "Hour:      $hour<br>";
                echo "Minute:    $minute<br>";
                echo "Activity:  $activity<br><br>";
                echo "</pre>";
           }
        }
        else
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

        // Select the database
        $query="select * from $table";

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
        else
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

        // Select the database
        $query="select * from $table";

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
                echo "<pre>";
                echo "Username:             $username<br>";
                echo "Title:                $title<br>";
                echo "Seller:               $seller<br>";
                echo "Category:             $category<br>";
                echo "End Day:              $end_day<br>";
                echo "End Hour:             $end_hour<br>";
                echo "End Minute:           $end_min<br>";
                echo "Bid Day:              $bid_day<br>";
                echo "Bid Hour:             $bid_hour<br>";
                echo "Bid Minute:           $bid_min<br>";
                echo "Bid Amount:           $bid_amt<br>";
                echo "Display Notification: $disp_notif<br><br>";
                echo "</pre>";
           }
        }
        else
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
