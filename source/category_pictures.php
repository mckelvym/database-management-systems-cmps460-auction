<?php
include_once ("common.php");

$dbinfo = new dbinfo_t ();
echo_header ($dbinfo);
$current_script = current_script ();
$mode = get ("mode");

if (empty ($mode))
{
	cout ("Listing of avialable pictures.");
	cout ("Pick a category:");

	$options = "";
	$options = $options.option ("Art", "Art");
	$options = $options.option ("Books", "Books");
	$options = $options.option ("Clothes", "Clothes");
	$options = $options.option ("Collectibles", "Collectibles");
	$options = $options.option ("Electronics", "Electronics");
	$options = $options.option ("Entertainment", "Entertainment");
	$options = $options.option ("Jewelry", "Jewelry");
	$options = $options.option ("Sporting Goods", "Sporting Goods");
	$options = $options.option ("Toys", "Toys");
	echo form_begin ("$current_script?mode=view", "post");
	echo select ("category", $options);
	echo submit_input ("Select");
}
else
{
	$file = str_replace (" ", "_", strtolower (post ("category")));
	for ($i = 1; $i <= 3; $i++)
	{
		cout (local_img ("$file$i.jpg"));
		cout ("$file$i.jpg");
		cout ("");
	}
}

echo_footer ($dbinfo);

?>
