<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');

$basedir = dirname(__FILE__) . '/html';


$files = scandir($basedir);


$files=array('pastvolumes?id=74.html');

$files=array('pastvolumes?id=92.html');

//$files=array('pastvolumes?id=153.html');
//$files=array('pastvolumes?id=157.html');

/*
$files = array();
for ($i = 132; $i <= 156; $i++)
{
	$files[] = "pastvolumes?id=$i.html";
}*/

$ids=array(
//130, 131, 128, 126,127, 125,124,122,123
//121,120,119,118,117,114,113,112
//111,110,109,108,107,106
//105,104,103,102	
//101, 116
//98
//99
//97
124
);

$ids=array(
363
);

$ids=array(
321//54
);

$files = array();

foreach ($ids as $i)
{
	$files[] = "pastvolumes?id=$i.html";
}

$pdfs = array();


foreach ($files as $filename)
{
	echo "filename=$filename|\n";
	
	if (preg_match('/\d+\.html$/', $filename))
	{	
		$html = file_get_contents($basedir . '/' . $filename);
		
		if ($html == '') {
		} else {

		$dom = str_get_html($html);


		$journal = '';
		$issn = '';
		$volume = '';
		$issue = '';
		$year = '';
		$month = '';
		$day = '00';
		$date = '';
		
		
		// <p style="margin-bottom: 20px; padding: 0px; text-align: justify; color: #585756; font-family: Arial, Helvetica, sans-serif;"><strong>[Taxonomy &amp; Systematics]</strong>&nbsp;First report of kinorhynchs from Singapore, with the description of three new species. Martin V. Sørensen, Ludwik Gąsiorowski, Phillip V. Randsø, Nuria Sánchez &amp; Ricardo C. Neves Pp. 3–27. [<a style="outline: 0px;" href="/nus/images/data/raffles_bulletin_of_zoology/vol64/64rbz003-027.pdf">pdf</a>]</p>
		$ps = $dom->find('p');
		foreach ($ps as $p)
		{
			echo $p->plaintext . "\n";
			
			
			$reference = null;
			
			if (preg_match('/\]&nbsp;(?<title>.*)[\.|\?](\s+|&nbsp;)(?<authorstring>.*)\.?(\s+|&nbsp;)Pp.\s+(?<spage>\d+)[–|-](?<epage>\d+)\b/Uu', $p->plaintext, $m))
			{
				//print_r($m);
				
				$reference = new stdclass;
				
				$reference->genre = 'article';
				
				$reference->title = $m['title'];
				
				$reference->title = preg_replace('/^\s+/u', '', $reference->title);
				$reference->title = str_replace('&nbsp;', '', $reference->title);
				
				$reference->authors = authors_from_string($m['authorstring'], true);
				
				$reference->journal = 'The Raffles Bulletin of Zoology';
				$reference->issn = '0217-2445';
				$reference->volume = 63;//62;//64;
				
				$reference->spage = $m['spage'];
				$reference->epage = $m['epage'];
				
				$reference->year = 2015;// 2016;
				//$reference->date = '2014-12-31';
			}
			
			if ($reference)
			{
				foreach ($p->find('a') as $a)
				{
					$reference->pdf = 'http://lkcnhm.nus.edu.sg' . $a->href;
					
					$pdfs[] = $reference->pdf;
				}
			
			
				//print_r($reference);
				
				echo reference_to_ris($reference);
			}
			
			
		}		
		
		

		$h1s = $dom->find('h1');
		foreach ($h1s as $h1)
		{
			echo $h1->plaintext . "\n";
			
			$p = $h1->plaintext;
			
			// RBZ 55 (1): 1–222. 28th February 2007
			// RBZ 55 (2):223–401. 31 August 2007
			// RBZ 56 (1):1–209. 29 February 2008
						
			if (preg_match('/RBZ\s+(?<volume>\d+)\s*\((?<issue>\d+)\):(\s+Pp\.)?\s*(?<spage>\d+)[–|-|–|-](?<epage>\d+)\.(\s+(?<day>\d+)(th)?)?\s+(?<month>\w+)\s+(?<year>[0-9]{4})/u', $h1->plaintext, $m))
			{
				print_r($m);
				
				$volume = $m['volume'];
				$issue = $m['issue'];
				$year = $m['year'];
				$month = $m['month'];
				$day = str_pad($m['day'], 2, '0', STR_PAD_LEFT);
			
				$date = date("Y-m-d", strtotime($day . ' ' . $month . ' ' . $year));
			
				//echo $date . "\n";
				
				$journal = 'The Raffles Bulletin of Zoology';
				$issn = '0217-2445';
			}
			
			if ($h1->plaintext == 'RBZ 56 (1):1–209. 29 February 2008')
			{
				echo "xxx\n";
				
				$journal = 'The Raffles Bulletin of Zoology';
				$issn = '0217-2445';
				$volume = 56;
				$issue = 1;	
				$date = '2008-02-29';	
				$year = 2008;		
			}
			
			// RBZ 39 (1): Pp. i–264. May 1991
			if (preg_match('/RBZ\s+(?<volume>\d+)\s*\((?<issue>\d+)\):(\s+Pp\.)?\s*(?<spage>(\d+|i))[–|-|–](?<epage>\d+)\.\s+(?<month>\w+)\s+(?<year>[0-9]{4})/u', $h1->plaintext, $m))
			{
				print_r($m);
				
				$volume = $m['volume'];
				
				$issue = $m['issue'];
				
				$year = $m['year'];
				$month = $m['month'];
				//$day = '00';
			
				$date = date("Y-m-00", strtotime($month . ' ' . $year));
			
				//echo $date . "\n";
				
				$journal = 'The Raffles Bulletin of Zoology';
				$issn = '0217-2445';
				
				//exit();
			}
			
			
			// RBZ 37 (1&2): Pp. 1–174. December 1989
			if (preg_match('/RBZ\s+(?<volume>\d+)\s*\((?<issue>1&amp;2)\):(\s+Pp\.)?\s*(?<spage>\d+)[–|-|–](?<epage>\d+)\.\s+(?<month>\w+)\s+(?<year>[0-9]{4})/u', $h1->plaintext, $m))
			{
				print_r($m);
				
				$volume = $m['volume'];
				
				$issue = str_replace('&amp;', '&', $m['issue']);
				
				$year = $m['year'];
				$month = $m['month'];
				//$day = '00';
			
				$date = date("Y-m-00", strtotime($month . ' ' . $year));
			
				//echo $date . "\n";
				
				$journal = 'The Raffles Bulletin of Zoology';
				$issn = '0217-2445';
				
				//exit();
			}
			
		
			// The Bulletin of the Raffles Museum, Vol. 1 (September 1928)
			if (preg_match('/The Bulletin of the Raffles Museum,\s+Vol.\s+(?<volume>\d+)\s*\((?<month>\w+)\s+(?<year>[0-9]{4})\)/u', $h1->plaintext, $m))
			{
				print_r($m);
				
				$volume = $m['volume'];
				$year = $m['year'];
				$month = $m['month'];
				//$day = '00';
			
				$date = date("Y-m-00", strtotime($month . ' ' . $year));
			
				//echo $date . "\n";
				
				$journal = 'The Bulletin of the Raffles Museum';
				
				//exit();
			}
		}
			
			
	
		if ($year != '')
		{

			$ps = $dom->find('p');
			foreach ($ps as $p)
			{
				 echo $p->plaintext . "\n";
				 echo $p->innertext . "\n";
				 echo "\n";
				
				
				$reference = null;
							
				
				if (!$reference)
				{
			
					if (preg_match('/(?<authorstring>(([A-Z]{2,}(-[A-Z]+)?),\s+([A-Z]\.)+)((\s*(,|&amp;))\s*([A-Z]{2,}(-[A-Z]+)?),\s+([A-Z]\.)+)*)\s+(?<title>.*)\s+P[p]?\.?\s*(?<spage>[0-9i]+)([-]\s*(?<epage>[0-9i]+))?\b/u', $p->plaintext, $m))
					{
						//print_r($m);
				
						$reference = reference_from_matches($m);
					}
				}

				if (!$reference)
				{
					echo "xxx\n";
					
					
					$pattern = '';
					
					$author = '(\w+,\s+([A-Z]\.(-?[A-Z]\.)*))';
					
					$pattern = '/(?<authorstring>' . $author .  '((,\s+| &amp; )' . $author . ')*' . ')' . '/u';

					$pattern = '/(?<authorstring>' . $author .  '((,\s+| &amp; )' . $author . ')*' . ')' .  '\s+(?<title>.*)\s+P[p]?\.?\s*(?<spage>[0-9i]+)([-]\s*(?<epage>[0-9i]+))?' . '/u';
					
					
					
					//if (preg_match('/(?<authorstring>(([A-Z]{2,}(-[A-Z]+)?),\s+([A-Z]\.[-]?)+)((\s*(,|&amp;))\s*([A-Z]{2,}(-[A-Z]+)?),\s+([A-Z]\.[-]?)+)*)\s+(?<title>.*)\s+P[p]?\.?\s*(?<spage>[0-9i]+)([-]\s*(?<epage>[0-9i]+))?\b/u', $p->plaintext, $m))
					if (preg_match($pattern, $p->plaintext, $m))
					{
						print_r($m);
				
						$reference = reference_from_matches($m);
					}
				}


			
				if ($reference)
				{
			
					$reference->genre = 'article';
					
					$reference->notes = $p->plaintext;
					
					//$reference->title = html_entity_decode($reference->title);
					
					
					
					$reference->journal = $journal;
					if ($issn != '')
					{
						$reference->issn = $issn;
					}
					$reference->volume = $volume;
					if ($issue != '')
					{
						$reference->issue = $issue;
					}					
					$reference->year = $year;
					$reference->date = $date;
				
					foreach ($p->find('a') as $a)
					{
						$reference->pdf = 'http://lkcnhm.nus.edu.sg' . str_replace(' ', '%20', $a->href);				
					}
					
					if (isset($reference->pdf))
					{
						$pdfs[] = $reference->pdf;
					}
				
					//print_r($reference);
					
					if (isset($reference->pdf))
					{					
						echo reference_to_ris($reference);
					}
				
				}
				else
				{
					//echo "*** not parsed ***\n";
					//exit();
				}
			}
			}
		}
	}
}

echo join("\n", $pdfs) . "\n";
		
?>

