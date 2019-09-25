<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');


/*
http://lkcnhm.nus.edu.sg/nus/pdf/PUBLICATION/Raffles%20Bulletin%20of%20Zoology/Past%20Volumes/RBZ%2011/11brm001-389.pdf
http://lkcnhm.nus.edu.sg/nus/pdf/PUBLICATION/Raffles%20Bulletin%20of%20Zoology/Past%20Volumes/RBZ%2015/15brmiii-209.pdf
http://lkcnhm.nus.edu.sg/nus/pdf/PUBLICATION/Raffles%20Bulletin%20of%20Zoology/Past%20Volumes/RBZ%2020/20brm005-299.pdf
http://lkcnhm.nus.edu.sg/nus/pdf/PUBLICATION/Raffles%20Bulletin%20of%20Zoology/Past%20Volumes/RBZ%2029/29brm006-112.pdf
http://lkcnhm.nus.edu.sg/nus/pdf/PUBLICATION/Raffles%20Bulletin%20of%20Zoology/Past%20Volumes/RBZ%2035/35bnm001-044.pdf
*/

// BULLETIN OF THE RAFFLES MUSEUM (1928 TO 1960)
// BULLETIN OF THE NATIONAL MUSEUM (1970)
// THE RAFFLES BULLETIN OF ZOOLOGY (1988 TO 2013)

$urls = array(
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=100',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=101',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=102',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=103',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=104',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=105',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=106',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=107',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=108',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=109',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=110',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=111',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=112',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=113',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=114',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=115',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=116',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=117',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=118',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=119',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=120',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=121',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=122',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=123',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=124',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=125',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=126',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=127',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=128',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=129',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=130',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=131',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=132',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=133',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=134',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=135',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=136',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=137',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=138',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=140',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=141',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=142',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=143',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=144',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=145',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=146',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=147',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=148',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=149',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=150',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=151',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=152',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=153',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=154',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=155',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=157',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=321',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=363',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=54',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=74',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=75',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=76',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=77',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=78',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=79',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=84',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=85',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=86',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=87',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=88',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=89',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=90',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=91',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=92',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=93',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=94',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=95',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=96',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=97',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=98',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=99',
'http://lkcnhm.nus.edu.sg/nus/index.php/pastvolumes?id=156'
);

foreach ($urls as $url)
{
	$filename = '';
	
	// if no name use basename
	if ($filename == '')
	{
		$filename = basename($url) . '.html';
	}
	
	$html = get($url);

	file_put_contents('html/' . $filename, $html);
}

?>
