<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');

$basedir = dirname(__FILE__) . '/html';

$files = scandir($basedir);

// debugging
$files=array('volume-382.html');

$pdfs = array();

/*
<div class="publication-layout">
    <div class="sf-title text-blue">TAN, K.S. &amp; SIGURDSSON, J.B</div>
    <div class="sf-sub text-blue">A new species of <i>Thais</i> (Gastropoda: Muricidae) from Singapore and Peninsular Malaysia</div>
    <div class="sf-excerpt"><p>Pp. 205-211</p>
</div>

        
    <a href="https://lkcnhm.nus.edu.sg/app/uploads/2017/06/38rbz205-211.pdf" target="_new">
      Download PDF (606 KB)    </a>
    <hr class="sf-hr">
  </div>
*/


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
		
		// <h2>The Raffles Bulletin of Zoology Volume 38(2)</h2>
		foreach ($dom->find('h2') as $h)
		{
			if (preg_match('/The Raffles Bulletin of Zoology Volume (?<volume>\d+)\((?<issue>\d+)\)/', $h->plaintext, $m))
			{
				$journal 	= 'The Raffles Bulletin of Zoology';
				$issn 		= '0217-2445';
				$volume 	= $m['volume'];
				$issue 		= $m['issue'];
				$year 		= 1990;
			}
		}
		
		// get article details
		$divs = $dom->find('div[class=publication-layout]');
		foreach ($divs as $div)
		{
			
			$reference = new stdclass;
			
				$reference->journal 	= $journal;
				$reference->issn 		= $issn;
				$reference->volume 		= $volume;
				$reference->issue 		= $issue;
				$reference->year 		= $year;
			
			
			foreach ($div->find('div[class=sf-title text-blue]') as $d)
			{
				$reference->authorstring = $d->plaintext;
				$reference->authors = authors_from_string($reference->authorstring);
			}
			
			
			foreach ($div->find('div[class=sf-sub text-blue]') as $d)
			{
				$reference->title = $d->plaintext;
			}

			foreach ($div->find('div[class=sf-excerpt]') as $d)
			{
				if (preg_match('/Pp. (?<spage>\d+)-(?<epage>\d+)/', $d->plaintext, $m))
				{
					$reference->spage = $m['spage'];
					$reference->epage = $m['epage'];
				}
			}
			
			foreach ($div->find('a') as $d)
			{
				$reference->pdf = $d->href;
				
				$pdfs[] = $reference->pdf;
			}
			
			
			//print_r($reference);
			
			// $reference = reference_from_matches($m);
			
			echo reference_to_ris($reference);
			

		}
		
	}

}

echo join("\n", $pdfs) . "\n";
		
?>

