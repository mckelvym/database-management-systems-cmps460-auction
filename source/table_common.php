<?php

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

	function init ($main_class_)
	{
		$this->main_class = $main_class_;
		return $this->main_class;
	}

	function set_main_class ($main_class_)
	{
		return $this->init ($main_class_);
	}

	function get_main_class ()
	{
		return $this->main_class;
	}

	function table_begin ()
	{
		return "<table class=\"$this->main_class\">\n";
	}

	function table_end ()
	{
		return "</table>\n";
	}

	function table_head_begin ($custom_class = "")
	{
		if (empty ($custom_class))
			return "<thead class=$this->main_class>\n";
		else
			return "<thead class=$custom_class>\n";
	}

	function table_head_end ()
	{
		return "</thead>\n";
	}

	function table_body_begin ($custom_class = "")
	{
		if (empty ($custom_class))
			return "<tbody class=$this->main_class>\n";
		else
			return "<tbody class=$custom_class>\n";
	}

	function table_body_end ()
	{
		return "</tbody>\n";
	}

	function tr ($s, $custom_class = "")
	{
		if (empty ($custom_class))
			return "\t<tr class=$this->main_class>\n\t$s\n\t</tr>\n";
		else
			return "\t<tr class=$custom_class>\n\t$s\n\t</tr>\n";
	}

	function tr_begin ($custom_class = "")
	{
		if (empty ($custom_class))
			return "\t<tr class=$this->main_class>\n";
		else
			return "\t<tr class=$custom_class>\n";
	}

	function tr_end ()
	{
		return "\t</tr>\n";
	}

	function td ($s, $custom_class = "")
	{
		return $this->td_span ($s, $custom_class);
	}

	function td_span ($s, $custom_class = "", $span = 0)
	{
		if ($span == 0)
		{
			if (empty ($custom_class))
				return "\t<td class=$this->main_class>\n\t$s\n\t</td>\n";
			else
				return "\t<td class=$custom_class>\n\t$s\n\t</td>\n";
		}
		else
		{
			if (empty ($custom_class))
				return "\t<td class=$this->main_class colspan=$span>\n\t$s\n\t</td>\n";
			else
				return "\t<td class=$custom_class colspan=$span>\n\t$s\n\t</td>\n";
		}
	}

}

?>