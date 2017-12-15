<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');

$basedir = dirname(__FILE__) . '/html';

$files = scandir($basedir);

// debugging
//$files=array('56.htm');

$pdfs = array();


foreach ($files as $filename)
{
	// echo "filename=$filename\n";
	
	if (preg_match('/\.html?$/', $filename))
	{	
		$html = file_get_contents($basedir . '/' . $filename);
		
		$dom = str_get_html($html);


		$journal = '';
		$issn = '';
		$volume = '';
		$issue = '';
		$year = '';

		// get issue details
		
		//<span style="font-weight: bold;">Vol. 51 No. 2, 2012</span>
		echo "xxx\n";
		
		
		$spans = $dom->find('big span');
		foreach ($spans as $span)
		{
		
			//echo "** " . $span->plaintext . "\n";
			
			if (preg_match('/Vol\.\s+(?<volume>\d+)\s+No\.\s+(?<issue>\d+),\s+(?<year>[0-9]{4})/', $span->plaintext, $m))
			{
				$volume = $m['volume'];
				$issue = $m['issue'];
				$year = $m['year'];
			}

			// Vol. 56, 2017
			if (preg_match('/Vol\.\s+(?<volume>\d+),\s+(?<year>[0-9]{4})/', $span->plaintext, $m))
			{
				//print_r($m);
				$volume = $m['volume'];
				$year = $m['year'];
			}
			
			// Vol. 33 No. , 1994
			if (preg_match('/Vol\.\s+(?<volume>\d+)\s+No\.\s+,\s+(?<year>[0-9]{4})/', $span->plaintext, $m))
			{
				$volume = $m['volume'];
				$year = $m['year'];
			}
		
		
		}
		
		//exit();
		
		
		
		// get article details
		$tds = $dom->find('td');
		foreach ($tds as $td)
		{
			$reference = new stdclass;
			
			$reference->genre = 'article';
			$reference->journal = 'Zoological Studies';
			$reference->issn = '1021-5506';
			
			$reference->volume = $volume;
			$reference->issue = $issue;
			$reference->year = $year;
			
			
			
				
			//echo $td->plaintext . "\n";
			
			foreach ($td->find('a') as $a)
			{
				//echo $a->href . "\n";
				
				if (preg_match('/.pdf/', $a->href))
				{
					$reference->pdf = 'http://zoolstud.sinica.edu.tw/' . $a->href;
					
					if (preg_match('/\/(?<spage>\d+).pdf/', $reference->pdf, $m))
					{
						$reference->spage = $m['spage'];
					}
					
					 $reference->pdf = str_replace('/../', '/',  $reference->pdf);
					
					$pdfs[] = $reference->pdf;
				}
				if (preg_match('/.html?/', $a->href))
				{
					$reference->url = 'http://zoolstud.sinica.edu.tw/' . $a->href;
					
					$reference->url = str_replace('/../', '/',  $reference->url);
				}
			}

			
			foreach ($td->find('span[1]') as $span)
			{
				//echo $span->outertext . "\n";
				//echo $span->plaintext . "\n";
				$reference->title = $span->plaintext;
				$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
			}
			
			
			// <span style="color: rgb(117, 115, 115);">
			
			//echo "\n\n----\n\n";
			
			foreach ($td->find('span[style=color: rgb(117, 115, 115);]') as $span)
			{
				//echo $span->outertext . "\n";
				//echo "***". $span->plaintext . "***\n";
				
				$go = true;
				
				if (preg_match('/^\s*\($/', $span->plaintext))
				{
					$go = false;
				}
				if (preg_match('/^\s*doi:\)$/', $span->plaintext))
				{
					$go = false;
				}

				if (preg_match('/^\s*doi:(?<doi>10.*)/', $span->plaintext, $m))
				{
					$reference->doi = $m['doi'];
					$go = false;
				}


				if (preg_match('/^\s*\)$/', $span->plaintext))
				{
					$go = false;
				}
				
				if ($go)
				{
					$authorstring = '';
				
					$matched = false;
				
					if (!$matched)
					{				
						if (preg_match('/(?<authorstring>.*)\s+\(doi:(?<doi>.*)\)/U', $span->plaintext, $m))
						{
							$authorstring = $m['authorstring'];
							$reference->doi = $m['doi'];
						
							$matched = true;
						}
					}
				
					if (!$matched)
					{				
						$authorstring = trim($span->plaintext);
					}			
				
					if ($authorstring != '')
					{
						$authorstring = preg_replace('/\s+\($/', '', $authorstring);
						$authorstring = preg_replace('/([A-Z])\.([A-Z])/', '$1. $2', $authorstring);
						$authorstring = preg_replace('/\s\s+/', ' ', $authorstring);
						$authorstring = preg_replace('/,?\s+and\s+/u', '|', $authorstring);
						$authorstring = preg_replace('/,\s+/u', '|', $authorstring);
					
						$reference->authors = explode('|', $authorstring);
				
					}
				
					//echo $authorstring . "\n";
				}								
				
			}
			
			
			
			
			// $reference = reference_from_matches($m);
			if (isset($reference->pdf))
			{
				echo reference_to_ris($reference);
			}
			

		}
		
	}

}

echo join("\n", $pdfs) . "\n";
		
?>

