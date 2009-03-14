<?php

// Unordered Lists
function ul_begin ()
{
	return "<ul>\n";
}

function ul_end ()
{
	return "</ul>\n";
}

function ul_wrap ($list_elems)
{
	return ul_begin ().$list_elems.ul_end ();
}

// Ordered Lists
function ol_begin ()
{
	return "<ol>\n";
}

function ol_end ()
{
	return "</ol>\n";
}

function ol_wrap ($list_elems)
{
	return ol_begin ().$list_elems.ol_end ();
}

// List elements
function li_begin ()
{
	return "<li>\n";
}

function li_end ()
{
	return "</li>\n";
}

function li_wrap ($list_elem)
{
	return li_begin ().$list_elem.li_end ();
}
?>