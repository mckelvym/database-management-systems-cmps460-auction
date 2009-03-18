<?php

class dbinfo_t
{
	var $host;
	var $user;
	var $pass;
	var $dbname;
	var $dblink;
	var $admin;

	function init ()
	{
		$this->host = "calvados.ucs.louisiana.edu";
		$this->user = "cs4601i";
		$this->pass = "ZV6R4L";
		$this->dbname = "cs4601_i";
		$this->admin = 0;
	}

	function connect ()
	{
		$success = 1;
		$dblink = mysql_connect ($this->host,
					 $this->user,
					 $this->pass)
			or ($success = 0);
		if (!$success)
			return $success;

		mysql_select_db ($this->dbname)
			or ($success = 0);
		return $success;
	}

	function close ()
	{
		mysql_close ($this->dblink);
	}

	function query ($q)
	{
		return mysql_query ($q);
	}

	function get_link ()
	{
		return $this->dblink;
	}

	function set_admin ()
	{
		$this->admin = 1;
	}

	function is_admin ()
	{
		return $this->admin;
	}
}

?>
