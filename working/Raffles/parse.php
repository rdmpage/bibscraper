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
363,
321,
74,
76,
78,
84,
86,
88,
90,
92,
54,
75,
77,
79,
85,
87,
89,
91,
93);

$ids=array(
94,
95,
92, 93
);

// 53
$ids=array(
//96,97,98,99,
//115, 100
98
);

$ids=array(
363
);

$files = array();

foreach ($ids as $i)
{
	$files[] = "pastvolumes?id=$i.html";
}

$pdfs = array();


foreach ($files as $filename)
{
	echo "filename=$filename\n";
	
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
					if (preg_match_all('/<strong>\s*(?<authorstring>.*)\s*<\/strong>/Uu', $p->innertext, $m))
					{
						//print_r($m);
						
						$n = count($m[0]);
						$authorstring = $m[0][$n-1];
						
						$reference = new stdclass;
						
						$reference->authors = array();
						
						foreach ($m['authorstring'] as $a)
						{
							$a = preg_replace('/<em>/', '', $a);
							$a = preg_replace('/<\/em>/', '', $a);
							$a = preg_replace('/\.$/', '', $a);
							
							$a = preg_replace('/<br \/>/', '', $a);
							$a = preg_replace('/, Jr/', '', $a);
							$a = preg_replace('/,\s+/', '|', $a);
							$a = preg_replace('/ &amp; /', '|', $a);
							$parts = explode("|", $a);
							$reference->authors = array_merge($reference->authors, $parts);
						}
						
						$matched = false;
						
						if (!$matched)
						{
							if (preg_match('/^(?<title>.*)\s*<strong>(.*)>\.?\s*P[p]?\.?\s*(?<spage>[0-9i]+)([-|–]\s*(?<epage>[0-9i]+))\.?\s+/Uu', $p->innertext, $m))
							{
								//print_r($m);
				
								$reference = reference_from_matches($m, $reference);
								
								$matched = true;
							}
						}
						
						if (!$matched)
						{
						
							
							$authorstring = str_replace("/", "\/", $authorstring);
							
							$pattern = '/' . $authorstring . '\.\s+(?<title>.*)\.\s+P[p]?\.?\s*(?<spage>[0-9i]+)([-|–]\s*(?<epage>[0-9i]+))?./u';
							
							echo "pattern = $pattern\n";

							if (preg_match($pattern, $p->innertext, $m))
							{
								//print_r($m);
				
								$reference = reference_from_matches($m, $reference);
								
								$matched = true;
							}


						}						
						
											
						
						
						
					}


				}				
				
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
					echo "xxxx\n";
						
					// 			if (preg_match('/(?<authorstring>(([A-Z]{2,}),\s+([A-Z]\.(-[A-Z]\.)?)+)((\s*(,|&amp;))\s*([A-Z]{2,}(-[A-Z]+)?),\s+([A-Z]\.)+)*)/u', $p->plaintext, $m))

					if (preg_match('/(?<authorstring>.*)/u', $p->plaintext, $m))
					{
						print_r($m);
				
						$reference = reference_from_matches($m);
					}
				}

				if (!$reference)
				{
			
					if (preg_match('/(?<authorstring>ANON\.)\s+(?<title>.*)\s+P[p]?\.\s+(?<spage>\d+)([-](?<epage>\d+))?\./u', $p->plaintext, $m))
					{
						//print_r($m);
				
						$reference = reference_from_matches($m);
					}
				}
				
				// ROEWER, C.FR. Spolia Mentawiensia: Opiliones. Pp. 134-136. [pdf, 268kb]
				if (!$reference)
				{
			
					if (preg_match('/(?<authorstring>ROEWER, C\.FR\.)\s+(?<title>.*)\s+P[p]?\.\s+(?<spage>\d+)([-](?<epage>\d+))?\./u', $p->plaintext, $m))
					{
						//print_r($m);
				
						$reference = reference_from_matches($m);
					}
				}
				
				
				if (!$reference)
				{
			
					if (preg_match('/(?<a>([A-Z](\.-[A-Z])?\.\s*)+\s+([A-Z][a-z]+)(\s*(,|&amp;)\s*([A-Z](\.-[A-Z])?\.\s*)+\s+([A-Z][a-z]+))*)\.\s+(?<title>.*)\s+Pp\.\s+(?<spage>\d+)[-](?<epage>\d+)\./u', $p->plaintext, $m))
					{
						print_r($m);
				
						$reference = reference_from_matches($m);
						
						$authorstring = $m['a'];
						
						$authorstring = preg_replace('/ &amp; /', '|', $authorstring);
						$authorstring = preg_replace('/(\w+),\s+/', '$1|', $authorstring);
						
						$reference->authors = explode("|", $authorstring);
						
						
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

