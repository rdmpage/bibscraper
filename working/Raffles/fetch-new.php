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
'https://lkcnhm.nus.edu.sg/rbz/350-2/',
'https://lkcnhm.nus.edu.sg/rbz/volume-1/',
'https://lkcnhm.nus.edu.sg/rbz/volume-10/',
'https://lkcnhm.nus.edu.sg/rbz/volume-11/',
'https://lkcnhm.nus.edu.sg/rbz/volume-12/',
'https://lkcnhm.nus.edu.sg/rbz/volume-13/',
'https://lkcnhm.nus.edu.sg/rbz/volume-14/',
'https://lkcnhm.nus.edu.sg/rbz/volume-15/',
'https://lkcnhm.nus.edu.sg/rbz/volume-16/',
'https://lkcnhm.nus.edu.sg/rbz/volume-17/',
'https://lkcnhm.nus.edu.sg/rbz/volume-18/',
'https://lkcnhm.nus.edu.sg/rbz/volume-19/',
'https://lkcnhm.nus.edu.sg/rbz/volume-2/',
'https://lkcnhm.nus.edu.sg/rbz/volume-20/',
'https://lkcnhm.nus.edu.sg/rbz/volume-21/',
'https://lkcnhm.nus.edu.sg/rbz/volume-22/',
'https://lkcnhm.nus.edu.sg/rbz/volume-23/',
'https://lkcnhm.nus.edu.sg/rbz/volume-24/',
'https://lkcnhm.nus.edu.sg/rbz/volume-25/',
'https://lkcnhm.nus.edu.sg/rbz/volume-26/',
'https://lkcnhm.nus.edu.sg/rbz/volume-27/',
'https://lkcnhm.nus.edu.sg/rbz/volume-28/',
'https://lkcnhm.nus.edu.sg/rbz/volume-29/',
'https://lkcnhm.nus.edu.sg/rbz/volume-3/',
'https://lkcnhm.nus.edu.sg/rbz/volume-35/',
'https://lkcnhm.nus.edu.sg/rbz/volume-361/',
'https://lkcnhm.nus.edu.sg/rbz/volume-362/',
'https://lkcnhm.nus.edu.sg/rbz/volume-37/',
'https://lkcnhm.nus.edu.sg/rbz/volume-381/',
'https://lkcnhm.nus.edu.sg/rbz/volume-382/',
'https://lkcnhm.nus.edu.sg/rbz/volume-391/',
'https://lkcnhm.nus.edu.sg/rbz/volume-392/',
'https://lkcnhm.nus.edu.sg/rbz/volume-4/',
'https://lkcnhm.nus.edu.sg/rbz/volume-401/',
'https://lkcnhm.nus.edu.sg/rbz/volume-402/',
'https://lkcnhm.nus.edu.sg/rbz/volume-411/',
'https://lkcnhm.nus.edu.sg/rbz/volume-412/',
'https://lkcnhm.nus.edu.sg/rbz/volume-421/',
'https://lkcnhm.nus.edu.sg/rbz/volume-422/',
'https://lkcnhm.nus.edu.sg/rbz/volume-423/',
'https://lkcnhm.nus.edu.sg/rbz/volume-424/',
'https://lkcnhm.nus.edu.sg/rbz/volume-431/',
'https://lkcnhm.nus.edu.sg/rbz/volume-432/',
'https://lkcnhm.nus.edu.sg/rbz/volume-441/',
'https://lkcnhm.nus.edu.sg/rbz/volume-442/',
'https://lkcnhm.nus.edu.sg/rbz/volume-451/',
'https://lkcnhm.nus.edu.sg/rbz/volume-452/',
'https://lkcnhm.nus.edu.sg/rbz/volume-461/',
'https://lkcnhm.nus.edu.sg/rbz/volume-462/',
'https://lkcnhm.nus.edu.sg/rbz/volume-471/',
'https://lkcnhm.nus.edu.sg/rbz/volume-472/',
'https://lkcnhm.nus.edu.sg/rbz/volume-481/',
'https://lkcnhm.nus.edu.sg/rbz/volume-482/',
'https://lkcnhm.nus.edu.sg/rbz/volume-491/',
'https://lkcnhm.nus.edu.sg/rbz/volume-492/',
'https://lkcnhm.nus.edu.sg/rbz/volume-5/',
'https://lkcnhm.nus.edu.sg/rbz/volume-501/',
'https://lkcnhm.nus.edu.sg/rbz/volume-502/',
'https://lkcnhm.nus.edu.sg/rbz/volume-511-2/',
'https://lkcnhm.nus.edu.sg/rbz/volume-511/',
'https://lkcnhm.nus.edu.sg/rbz/volume-521/',
'https://lkcnhm.nus.edu.sg/rbz/volume-522/',
'https://lkcnhm.nus.edu.sg/rbz/volume-531/',
'https://lkcnhm.nus.edu.sg/rbz/volume-532/',
'https://lkcnhm.nus.edu.sg/rbz/volume-542/',
'https://lkcnhm.nus.edu.sg/rbz/volume-551/',
'https://lkcnhm.nus.edu.sg/rbz/volume-552/',
'https://lkcnhm.nus.edu.sg/rbz/volume-561/',
'https://lkcnhm.nus.edu.sg/rbz/volume-562/',
'https://lkcnhm.nus.edu.sg/rbz/volume-571/',
'https://lkcnhm.nus.edu.sg/rbz/volume-572/',
'https://lkcnhm.nus.edu.sg/rbz/volume-581/',
'https://lkcnhm.nus.edu.sg/rbz/volume-582/',
'https://lkcnhm.nus.edu.sg/rbz/volume-591/',
'https://lkcnhm.nus.edu.sg/rbz/volume-592/',
'https://lkcnhm.nus.edu.sg/rbz/volume-6/',
'https://lkcnhm.nus.edu.sg/rbz/volume-601/',
'https://lkcnhm.nus.edu.sg/rbz/volume-602/',
'https://lkcnhm.nus.edu.sg/rbz/volume-611/',
'https://lkcnhm.nus.edu.sg/rbz/volume-612/',
'https://lkcnhm.nus.edu.sg/rbz/volume-62/',
'https://lkcnhm.nus.edu.sg/rbz/volume-63/',
'https://lkcnhm.nus.edu.sg/rbz/volume-65/',
'https://lkcnhm.nus.edu.sg/rbz/volume-66/',
'https://lkcnhm.nus.edu.sg/rbz/volume-7/',
'https://lkcnhm.nus.edu.sg/rbz/volume-8/',
'https://lkcnhm.nus.edu.sg/rbz/volume-9/',
'https://lkcnhm.nus.edu.sg/rbz/voume-541/');

foreach ($urls as $url)
{
	$filename = '';
	
	// if no name use basename
	if ($filename == '')
	{
		$filename = str_replace('https://lkcnhm.nus.edu.sg/rbz/', '', $url);
		
		$filename  = str_replace('/', '', $filename) . '.html';
	}
	
	$html = get($url);

	file_put_contents('html/' . $filename, $html);
}

?>
