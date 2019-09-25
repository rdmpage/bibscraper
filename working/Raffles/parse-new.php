<?php

// extract all PDFs

require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');

$basedir = dirname(__FILE__) . '/html';


$files = scandir($basedir);

//$files=array('volume-9.html');

$pdfs = array();

foreach ($files as $filename)
{
	//echo "filename=$filename|\n";
	
	if (preg_match('/\.html$/', $filename))
	{	
		$html = file_get_contents($basedir . '/' . $filename);
		
		if ($html == '') {
		} else {

			$dom = str_get_html($html);
		
			foreach ($dom->find('a') as $a)
			{
				$url = $a->href;
				if (preg_match('/\.pdf/', $url))
				{
					echo "-- " . $a->href . "\n";
					
					$pdf = basename($url);
					
					//echo $pdf . "\n";
					
					//$sql = 'SELECT pdf FROM publications WHERE pdf LIKE "%' . $pdf . '" LIMIT 1;';

					//$sql = 'UPDATE publications SET pdf="' . $a->href . '", guid="' . $a->href 
					//	. '" WHERE issn="0217-2445" AND pdf LIKE "%' . $pdf . '";';

					$sql = 'UPDATE publications SET pdf="' . $a->href . '", guid="' . $a->href 
						. '" WHERE journal="The Bulletin of The Raffles Museum" AND pdf LIKE "%' . $pdf . '";';

					
					echo $sql . "\n";
				}
			}
		}
	}
}	
			

		
?>

