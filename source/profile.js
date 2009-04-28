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

function local_img (file)
{
	return "images/" + file;
}

function image_swap ()
{
	var image = document.getElementById ("dynimg");
	var pic = document.getElementById ("dynpicture");
	image.src = local_img (pic.value);
}
