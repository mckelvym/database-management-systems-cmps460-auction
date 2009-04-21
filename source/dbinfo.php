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

// Database information class, with useful utility functions for getting info
// out of the database





class dbinfo_t
{
	var $host; // database host
	var $user; // database user
	var $pass; // database password
	var $dbname; // database name
	var $dblink; // connection link
	var $debug; // debug mode enabled

	// Call this function to start the session
	// and initialize the variables
	function init ()
	{
		session_start ();
		$this->host = "calvados.ucs.louisiana.edu";
		$this->user = "cs4601i";
		$this->pass = "foursixty";
		$this->dbname = "cs4601_i";
		$this->admin = 0;
		$this->debug = true;
	}

	// Is debug enabled
	function debug ()
	{
		return $this->debug;
	}

	// Make a connection to the database
	// returns true if successful or false if not
	function connect ()
	{
		$success = 1;
		$this->dblink = mysql_connect ($this->host,
					 $this->user,
					 $this->pass)
			or ($success = 0);
		if (!$success)
			return $success;

		mysql_select_db ($this->dbname)
			or ($success = 0);
		return $success;
	}

	// Close the connection to the database
	// usually call this at the end of a script
	function close ()
	{
		mysql_close ($this->dblink);
	}

	// Make a query to the database and return the result
	function query ($q)
	{
		if ($this->debug)
			$result = mysql_query ($q) or die ('BAD QUERY: '.mysql_error ());
		else
			$result = mysql_query ($q);
		return $result;
	}

	// Get the link associated with the database
	// not usually needed but added here for convenience
	function get_link ()
	{
		return $this->dblink;
	}

	// Call all update functions
	function update_all ()
	{
		$this->update_time ();
		$this->update_user_info ();
		$this->update_closed_item_listings ();
	}

	// Update user information in session variables (realname, is an admin)
	function update_user_info ()
	{
		$username = $this->username ();
		$result = $this->query ("select realname, is_admin from user
where username = '$username'");
		$row = mysql_fetch_assoc ($result);
		$_SESSION['Admin'] = $row['is_admin'];
		$_SESSION['Realname'] = $row['realname'];
		mysql_free_result ($result);
	}

	// Attempt to login a user
	// Will return true if successful and false if not.
	// Additionally, will setup session vars for username
	// and admin or not
	function login ($username, $password)
	{
		$result = $this->query ("select username, realname, is_admin from user
where username = '$username' and password = '$password'");
		if (mysql_num_rows ($result) == 0)
		{
			unset ($_SESSION['Username']);
			unset ($_SESSION['Realname']);
			unset ($_SESSION['Admin']);
			mysql_free_result ($result);
			return false;
		}
		else
		{
			$row = mysql_fetch_assoc ($result);
			$_SESSION['Username'] = $row['username'];
			$_SESSION['Admin'] = $row['is_admin'];
			$_SESSION['Realname'] = $row['realname'];
			mysql_free_result ($result);
			$this->save_activity ("Logged In");
			return true;
		}
	}

	// Used by admin and also when registering - can login as a user without password
	function login_as ($username)
	{
		$result = $this->query ("select username, realname, is_admin from user
where username = '$username'");
		if (mysql_num_rows ($result) == 0)
		{
			unset ($_SESSION['Username']);
			unset ($_SESSION['Realname']);
			unset ($_SESSION['Admin']);
			mysql_free_result ($result);
			return false;
		}
		else
		{
			$row = mysql_fetch_assoc ($result);
			$_SESSION['Username'] = $row['username'];
			$_SESSION['Admin'] = $row['is_admin'];
			$_SESSION['Realname'] = $row['realname'];
			mysql_free_result ($result);
			$this->save_activity ("Logged In");
			return true;
		}
	}

	// Logout and save activity
	function logout ($is_delete = false)
	{
		if (!$is_delete)
			$this->save_activity ("Logged Out");
		unset ($_SESSION['Username']);
		unset ($_SESSION['Realname']);
		unset ($_SESSION['Admin']);
		session_destroy ();
		if (!$is_delete)
			header ("Location: index.php");
  	}


	// Check if visitor is logged in
	function logged_in ()
	{
		return isset ($_SESSION) && isset ($_SESSION['Username']);
	}

	// Check if visitor is admin
	function is_admin ()
	{
		return $this->logged_in () && $_SESSION['Admin'] == 1;
	}

	// Get logged in username
	function username ()
	{
		return $_SESSION['Username'];
	}

	// Get logged in in name of user
	function realname ()
	{
		return $_SESSION['Realname'];
	}

	// Update the time from the database into the session vars
	function update_time ()
	{
		$result = $this->query ("select day,hour,minute from user_activity
order by day desc, hour desc, minute desc limit 1");
		$row = mysql_fetch_assoc ($result);
		$day = $row['day'];
		$hour = $row['hour'];
		$minute = $row['minute'] + 1;

		if ($minute > 59)
		{
			$minute -= 60;
			$hour++;
		}

		if ($hour > 23)
		{
			$hour -= 24;
			$day++;
		}

		if ($minute < 10)
		{
			$minute = "0".$minute;
		}

		if ($hour < 10)
		{
			$hour = "0".$hour;
		}

		if ($day < 10)
		{
			$day = "0000".$day;
		}
		else if ($day < 100)
		{
			$day = "000".$day;
		}
		else if ($day < 1000)
		{
			$day = "00".$day;
		}
		else if ($day < 10000)
		{
			$day = "0".$day;
		}

		$_SESSION['Day'] = $day;
		$_SESSION['Hour'] = $hour;
		$_SESSION['Minute'] = $minute;
		mysql_free_result ($result);
	}

	// Will increment the site time by amount
	function increment_site_time ($amount)
	{
		if ($amount < 1)
			return false;
		$amount -= 1;
		$this->update_time ();
		$user = $this->username ();
		$day = $this->day ();
		$hour = $this->hour ();
		$minute = $this->minute () + $amount;
		if ($minute > 59)
		{
			$minute -= 60;
			$hour++;
		}
		if ($hour > 23)
		{
			$hour -= 24;
			$day++;
		}
		$this->query ("insert into user_activity values ('$user', $day, $hour, $minute, 'Current Time')");
		return true;
	}

	// Get the current day, this is called after update_time();
	function day ()
	{
		return $_SESSION['Day'];
	}

	// Get the current hour, this is called after update_time();
	function hour ()
	{
		return $_SESSION['Hour'];
	}

	// Get the current minute, this is called after update_time();
	function minute ()
	{
		return $_SESSION['Minute'];
	}

	// Save activity for the current user.
	function save_activity ($activity_description)
	{
		$this->update_time ();
		$user = $this->username ();
		$day = $this->day ();
		$hour = $this->hour ();
		$minute = $this->minute ();
		$activity_description = mysql_real_escape_string ($activity_description);

		$this->query ("insert into user_activity values ('$user', $day, $hour, $minute, '$activity_description')");
		return true;
	}

	// Must be admin, save activity for another user - user must exist
	function save_activity_for ($username, $activity_description)
	{
		if (!$this->is_admin ())
			return false;

		$this->update_time ();
		$user = $username;
		$day = $this->day ();
		$hour = $this->hour ();
		$minute = $this->minute ();
		$activity_description = mysql_real_escape_string ($activity_description);

		// determine if $username is a valid user
		$result = $this->query ("select count(*) as count from user where username = '$username'");
		if (mysql_num_rows ($result) == 0)
			return false;
		mysql_free_result ($result);

		$this->query ("insert into user_activity values ('$user', $day, $hour, $minute, '$activity_description')");
	}

	// Save a registration activity for the current user
	function save_registration ($username)
	{
		$this->update_time ();
		$user = $username;
		$day = $this->day ();
		$hour = $this->hour ();
		$minute = $this->minute ();
		$this->query ("insert into user_activity values ('$user', $day, $hour, $minute, 'Registered')");
		return true;
	}

	// Update all other bidder's notification status that they've been outbid, and also current_price for auction
	function update_auction_before_bid ($title, $seller, $category, $end_day, $end_hour, $end_minute, $bid_amount)
	{
		$this->query ("update bids_on set
display_notification = 'y'
where	display_notification = 'n'
AND 	item_title = '$title'
AND 	item_seller = '$seller'
AND 	item_category = '$category'
AND 	item_end_day = $end_day
AND 	item_end_hour = $end_hour
AND 	item_end_minute = $end_minute");
		$this->query ("update item_listing set
current_price = $bid_amount
where 	title = '$title'
AND 	seller = '$seller'
AND 	category = '$category'
AND 	end_day = $end_day
AND 	end_hour = $end_hour
AND 	end_minute = $end_minute");
	}

	// Updates all item listing buyer information for closed auctions
	function update_closed_item_listings ()
	{
		$day = $this->day ();
		$hr = $this->hour ();
		$min = $this->minute ();
		$result1 = $this->query ("select title, seller, category, end_day, end_hour, end_minute from item_listing
where 	(buyer = '' OR buyer = 'None')
AND	(end_day < $day
	OR (end_day = $day
		AND end_hour < $hr)
	OR (end_day = $day
		AND end_hour = $hr
		AND end_minute <= $min)
	)");
		if (mysql_num_rows ($result1) > 0)
		{
			while ($row = mysql_fetch_assoc ($result1))
			{
				$title = $row['title'];
				$seller = $row['seller'];
				$category = $row['category'];
				$endday = $row['end_day'];
				$endhr = $row['end_hour'];
				$endmin = $row['end_minute'];
				$result2 = $this->query ("select username, bid_amount from bids_on
where 	item_title = '$title'
AND 	item_seller = '$seller'
AND 	item_category = '$category'
AND 	item_end_day = $endday
AND 	item_end_hour = $endhr
AND 	item_end_minute = $endmin
order by bid_amount desc limit 1");
				// no buyer
				if (mysql_num_rows ($result2) == 0)
				{
					$this->query ("update item_listing set
buyer = 'None',
current_price = starting_price
where 	title = '$title'
AND 	seller = '$seller'
AND 	category = '$category'
AND 	end_day = $endday
AND 	end_hour = $endhr
AND 	end_minute = $endmin");
				}
				// buyer
				else
				{
					$row2 = mysql_fetch_assoc ($result2);
					$buyer = $row2['username'];
					$price = $row2['bid_amount'];
					$this->query ("update item_listing set
buyer = '$buyer',
current_price = $price
where 	title = '$title'
AND 	seller = '$seller'
AND 	category = '$category'
AND 	end_day = $endday
AND 	end_hour = $endhr
AND	end_minute = $endmin");
				}
				mysql_free_result ($result2);
			}
			mysql_free_result ($result1);
		}
	}

	// See if a user exists in the system
	function user_exists ($username)
	{
		$result = $this->query ("select username from user where username = '$username'");
		if (mysql_num_rows ($result) == 0)
		{
			mysql_free_result ($result);
			return false;
		}
		else
		{
			mysql_free_result ($result);
			return true;
		}
	}

	// Get a realname for a username
	function get_realname ($username)
	{
		$result = $this->query ("select realname from user where username = '$username'");
		if (mysql_num_rows ($result) == 0)
			return "ERROR";
		else
		{
			$row = mysql_fetch_assoc ($result);
			return $row['realname'];
		}
	}

	// Get the last logout time of a user
	function get_lastlogout ($username)
	{
		$result = $this->query ("select day, hour, minute from user_activity
where 	username = '$username'
AND	activity = 'Logged Out'
order by day desc, hour desc, minute desc
limit 1");
		if (mysql_num_rows ($result) == 0)
			return array (-1, -1, -1);
		else
		{
			list ($day, $hr, $min) = mysql_fetch_row ($result);
			return array ($day, $hr, $min);
		}
	}

	// Get the registration date of a user
	function get_registration_date ($username)
	{
		$result = $this->query ("select day, hour, minute from user_activity
where 	username = '$username'
AND	activity = 'Registered'
order by day desc, hour desc, minute desc
limit 1");
		if (mysql_num_rows ($result) == 0)
			return array (-1, -1, -1);
		else
		{
			list ($day, $hr, $min) = mysql_fetch_row ($result);
			return array ($day, $hr, $min);
		}
	}

	// Test if an auction already exists
	function auction_exists ($title, $seller, $category, $end_day, $end_hour, $end_minute)
	{
		$result = $this->query ("select seller from item_listing
where title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_minute");
		if (mysql_num_rows ($result) == 0)
		{
			mysql_free_result ($result);
			return false;
		}
		else
		{
			mysql_free_result ($result);
			return true;
		}
	}

	// Test if a bid already exists
	function bid_exists ($user, $title, $seller, $category, $end_day, $end_hour, $end_minute,
			     $bid_day, $bid_hour, $bid_minute)
	{
		$result = $this->query ("select username from bids_on
where 	username = '$user'
AND	item_title = '$title'
AND	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_minute
AND	bid_day = $bid_day
AND	bid_hour = $bid_hour
AND	bid_minute = $bid_minute");
		if (mysql_num_rows ($result) == 0)
		{
			mysql_free_result ($result);
			return false;
		}
		else
		{
			mysql_free_result ($result);
			return true;
		}
	}

	// Get the winner of an auction
	function get_winner ($title, $seller, $category, $end_day, $end_hour, $end_minute)
	{
		$result = $this->query ("select buyer, realname, current_price from item_listing, user
where	title = '$title'
AND	seller = '$seller'
AND	category = '$category'
AND	end_day = $end_day
AND	end_hour = $end_hour
AND	end_minute = $end_minute
AND	(buyer != '' OR buyer != 'None')
AND	buyer = username");
		if (mysql_num_rows ($result) == 0)
			return array (-1, -1, -1);
		else
		{
			list ($username, $realname, $bid_amount) = mysql_fetch_row ($result);
			return array ($username, $realname, $bid_amount);
		}
	}

	// Find the current highest bidder for an auction
	function get_highest_bidder ($title, $seller, $category, $end_day, $end_hour, $end_minute)
	{
		$result = $this->query ("select username, bid_amount from bids_on
where	item_title = '$title'
AND	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_minute
order by bid_amount desc");
		if (mysql_num_rows ($result) == 0)
			return array ("None", 0);
		else
		{
			list ($username, $bid_amount) = mysql_fetch_row ($result);
			return array ($username, $bid_amount);
		}
	}

	// Get the number of bids for an auction
	function get_num_bids ($title, $seller, $category, $end_day, $end_hour, $end_minute)
	{
		$result = $this->query ("select count(*) from bids_on
where	item_title = '$title'
AND	item_seller = '$seller'
AND	item_category = '$category'
AND	item_end_day = $end_day
AND	item_end_hour = $end_hour
AND	item_end_minute = $end_minute");
		list ($bid_count) = mysql_fetch_row ($result);
		return $bid_count;
	}

	//Begin - Changes made by Sayooj Valsan > # User Profile
	// Gets the user description
	function get_userdesc ($username)
	{
		$result = $this->query ("select description from user where username =  '$username'");
		$row = mysql_fetch_assoc ($result);
		return $row['description'];
	}
	//End - Changes made by Sayooj Valsan > # User Profile
	//Begin - Changes made by Sayooj Valsan <04-10-2009>
	function get_picture ($username)
	{
		$result = $this->query ("select picture from user where username = '$username'");
		$row = mysql_fetch_assoc ($result);
		return $row['picture'];
	}
	//End - Changes made by Sayooj Valsan <04-10-2009>

}

?>
