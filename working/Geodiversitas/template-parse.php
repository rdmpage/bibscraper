<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');

$basedir = dirname(__FILE__) . '/html';

$files = scandir($basedir);

// debugging
$files=array('19-1-morphologie-et-variations-du-toit-cranien-du-dipneuste-scaumenacia-curta-whiteaves-sarcopterygii-du-devonien-superieur-du-quebec.html');

$pdfs = array();


foreach ($files as $filename)
{
	// echo "filename=$filename\n";
	
	if (preg_match('/\.html$/', $filename))
	{	
		$html = file_get_contents($basedir . '/' . $filename);
		
		$dom = str_get_html($html);


		$journal = '';
		$issn = '';
		$volume = '';
		$issue = '';
		$year = '';

		// get issue details
		
		
		// get article details
		$h1s = $dom->find('h1');
		foreach ($h1s as $h1)
		{
			echo $h1->plaintext . "\n";
			
			
			$reference = new stdclass;
			
			// $reference = reference_from_matches($m);
			
			echo reference_to_ris($reference);
			

		}
		
	}

}

echo join("\n", $pdfs) . "\n";
		
?>

