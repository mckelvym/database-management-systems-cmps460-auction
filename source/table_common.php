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

// Table related common functions/class (for styles)






class table_common_t
{
/* 	for ($i = 0; $i < mysql_num_fields ($result); $i++) */
/* 		td (mysql_field_name ($result, $i)); */
/* 		for ($i = 0; $i < mysql_num_fields ($result); $i++) */
/* 		{ */
/* 			$field_name = mysql_field_name ($result, $i); */
/* 			td ($row[$field_name]); */
/* 		} */

	var $main_class;

	// initialize to use certain style
	function init ($main_class_)
	{
		$this->main_class = $main_class_;
		return $this->main_class;
	}

	// set to use certain style
	function set_main_class ($main_class_)
	{
		return $this->init ($main_class_);
	}

	// get which style it is using
	function get_main_class ()
	{
		return $this->main_class;
	}

	// begin the html table
	function table_begin ()
	{
		return "<table class=\"$this->main_class\">\n";
	}

	// end the html table
	function table_end ()
	{
		return "</table>\n";
	}

	// begin the html table head
	function table_head_begin ($custom_class = "")
	{
		if (empty ($custom_class))
			return "<thead class=$this->main_class>\n";
		else
			return "<thead class=$custom_class>\n";
	}

	// end the html table head
	function table_head_end ()
	{
		return "</thead>\n";
	}

	// begin the html table body
	function table_body_begin ($custom_class = "")
	{
		if (empty ($custom_class))
			return "<tbody class=$this->main_class>\n";
		else
			return "<tbody class=$custom_class>\n";
	}

	// end the html table body
	function table_body_end ()
	{
		return "</tbody>\n";
	}

	// html table row
	function tr ($s, $custom_class = "")
	{
		if (empty ($custom_class))
			return "\t<tr class=$this->main_class>\n\t$s\n\t</tr>\n";
		else
			return "\t<tr class=$custom_class>\n\t$s\n\t</tr>\n";
	}

	// html table row begin
	function tr_begin ($custom_class = "")
	{
		if (empty ($custom_class))
			return "\t<tr class=$this->main_class>\n";
		else
			return "\t<tr class=$custom_class>\n";
	}

	// html table row end
	function tr_end ()
	{
		return "\t</tr>\n";
	}

	// html table data
	function td ($s, $custom_class = "")
	{
		return $this->td_span ($s, $custom_class);
	}

	// html table data which spans several columns
	function td_span ($s, $custom_class = "", $span = 0, $align = "")
	{
		if (!empty ($align))
			$align = "align=$align";

		if ($span == 0)
		{
			if (empty ($custom_class))
				return "\t<td $align class=$this->main_class>\n\t$s\n\t</td>\n";
			else
				return "\t<td $align class=$custom_class>\n\t$s\n\t</td>\n";
		}
		else
		{
			if (empty ($custom_class))
				return "\t<td $align class=$this->main_class colspan=$span>\n\t$s\n\t</td>\n";
			else
				return "\t<td $align class=$custom_class colspan=$span>\n\t$s\n\t</td>\n";
		}
	}

}

?>