<?php

class dbinfo_t
{
	var $host;
	var $user;
	var $pass;
	var $dbname;
	var $dblink;
	var $admin;
	var $debug;

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

	// Attempt to login a user
	// Will return true if successful and false if not.
	// Additionally, will setup session vars for username
	// and admin or not
	function login ($username, $password)
	{
		$result = $this->query ("select username, realname, is_admin from user where username = '$username' and password = '$password'");
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
			return true;
		}
	}

	function logout ()
	{
		unset ($_SESSION['Username']);
		unset ($_SESSION['Realname']);
		unset ($_SESSION['Admin']);
		session_destroy ();
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
	function user ()
	{
		return $_SESSION['Username'];
	}

	// Get logged in in name of user
	function name ()
	{
		return $_SESSION['Realname'];
	}

	// Update the time from the database into the session vars
	function update_time ()
	{
		$result = $this->query ("select day,hour,minute from user_activity where activity = 'current_time' order by day desc, hour desc, minute desc limit 1");
		$row = mysql_fetch_assoc ($result);
		$_SESSION['Day'] = $row['day'];
		$_SESSION['Hour'] = $row['hour'];
		$_SESSION['Minute'] = $row['minute'];
		mysql_free_result ($result);
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
}

?>
