<?php
include_once ("common.php");

//$host="calvados.ucs.louisiana.edu";
//$user="yourCLID";
//$password="yourPassword";
//$database="4601_";


$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);

$table = get ("name");
if ($dbinfo->logged_in () && $dbinfo->is_admin ())
{
	cout ("Soon, you'll see information from the database table '$table'.");
	
	if ($table == "user")
	{
//        $connect = mysql_connect($host,$user,$password);
        print '<font size="5" color="blue">';
        print "$table Data</font><br>";

        // Select the database
//        @mysql_select_db($database) or die("Unable to select database");
        $query="select * from $table";

        // Run the query
        $results_id = mysql_query($query);
        if($results_id)
        {
           print '<table border=1>';
           print '<th>ID<th>PIN<th>NAME<th>TYPE<th>BALANCE<th>';
           // Get each row of the result
           while ($row = mysql_fetch_row($results_id))
           {
              print '<tr>';
              // Get each attribute in the row
              foreach($row as $attribute)
              {
                 print "<td>$attribute</td> ";
              }
              print '</tr>';
           }
        }
        else
        {
           // Display the query and the MySQL error message
           print "<br><br>QUERY FAILED !!! <br><br>QUERY = $query <br>
                  <br>ERROR = ";
           die (mysql_error());
        }
//        mysql_close($connect);
	}
	
	
	else if ($table == "user_activity")
	{
		cout ("Nothing yet");
	}
	else if ($table == "item_listing")
	{
		cout ("Nothing yet");
	}
	else if ($table == "bids_on")
	{
		cout ("Nothing yet");
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
