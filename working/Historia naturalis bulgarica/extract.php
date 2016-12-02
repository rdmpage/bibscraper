<?php


require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');



$basedir = dirname(__FILE__) . '/html';

$files = scandir($basedir);


//$files=array('22.html');


$pdfs = array();

foreach ($files as $filename)
{
	if (preg_match('/\.html$/', $filename))
	{	
		//echo $filename . "\n";

		$html = file_get_contents($basedir . '/' . $filename);
		
		//echo $html;


		// fix encoding
		//$html = mb_convert_encoding($html, 'UTF-8', 'Windows-1251');

		$dom = str_get_html($html);

		

		// volume details
		$volume = '';
		$issue = '';
		$year = '';

		
		$h1s = $dom->find('h1');
		foreach ($h1s as $h1)
		{
			if (preg_match('/Volume\s+(?<volume>\d+)\s+\((?<year>[0-9]{4})\)/', $h1->plaintext, $m))
			{
				//print_r($m);
		
				$volume = $m['volume'];
				$year = $m['year'];
			}
		}
		

		$count = 0;
		$ps = $dom->find('p[class=h_ind]');
		foreach ($ps as $p)
		{
			//echo $p->plaintext . "|\n";
			
			//echo $p->outertext . "\n";
			//echo "-----------------------\n";
	
			$reference = null;
			
			$matched = false;
			

	
			// parse
			if (!$matched)
			{
				if (preg_match('/<b>(?<authorstring>.*)<\/b>\s*&#8212;\s*(?<title>.*):\s+(?<spage>\d+)(&#8212;(?<epage>\d+))?\s*(.*<a href="(?<pdf>.*)">)?/u', $p->outertext, $m))
				{
					//print_r($m);
		
					$reference = new stdclass;
		
					//$reference->authorstring = 
		
					$reference->authors = authors_from_string($m['authorstring']);
		
					/*
					$n = count($reference->authors);
					for ($i = 0; $i < $n; $i++)
					{
						$reference->authors[$i] = preg_replace('/^(\w+(-\w+)?)\s+/u', "$1, ", $reference->authors[$i]);
					}
					*/
		
					$reference->title = $m['title'];
					
					$reference->journal = 'Historia naturalis bulgarica';
					$reference->issn = '0205-3640';
		
					$reference->volume = $volume;
					$reference->year = $year;
					
		
					$reference->spage = $m['spage'];
					if ($m['epage'] != '')
					{
						$reference->epage = $m['epage'];
					}

					if ($m['pdf'] != '')
					{
						$reference->pdf = 'http://www.nmnhs.com/' . $m['pdf'];
					}
					
			
					
					$matched = true;
				}
			}
				

			if (isset($reference))
			{
				$reference->notes = trim($p->plaintext);

				echo reference_to_ris($reference);
		
				//print_r($reference);
	
	
			}
		}
	}
}


//echo join("\n", $pdfs) . "\n";
	
?>
