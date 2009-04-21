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

// HTML list related common functions






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

function ul ($list_elems)
{
	return ul_wrap ($list_elems);
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

function ol ($list_elems)
{
	return ol_wrap ($list_elems);
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

function li ($list_elem)
{
	return li_wrap ($list_elem);
}
?>