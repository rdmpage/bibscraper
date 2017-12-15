<?php

// Gets metadata from <meta> tags

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');


$urls=array(
'http://www.bioline.org.br/abstract?id=sm03001&lang=en',
'http://www.bioline.org.br/abstract?id=sm03002&lang=en',
'http://www.bioline.org.br/abstract?id=sm04001&lang=en',
'http://www.bioline.org.br/abstract?id=sm05001&lang=en',
'http://www.bioline.org.br/abstract?id=sm05002&lang=en',
'http://www.bioline.org.br/abstract?id=sm05003&lang=en',
'http://www.bioline.org.br/abstract?id=sm07001&lang=en',
'http://www.bioline.org.br/abstract?id=sm07002&lang=en',
'http://www.bioline.org.br/abstract?id=sm07003&lang=en',
'http://www.bioline.org.br/abstract?id=sm07004&lang=en',
'http://www.bioline.org.br/abstract?id=sm08001&lang=en',
'http://www.bioline.org.br/abstract?id=sm08002&lang=en',
'http://www.bioline.org.br/abstract?id=sm08003&lang=en',
'http://www.bioline.org.br/abstract?id=sm11001&lang=en',
'http://www.bioline.org.br/abstract?id=sm11001&lang=fr',
'http://www.bioline.org.br/abstract?id=sm11002&lang=en',
'http://www.bioline.org.br/abstract?id=sm11002&lang=fr',
'http://www.bioline.org.br/abstract?id=sm11003&lang=en',
'http://www.bioline.org.br/abstract?id=sm11003&lang=fr',
'http://www.bioline.org.br/abstract?id=sm11004&lang=en',
'http://www.bioline.org.br/abstract?id=sm11004&lang=fr',
'http://www.bioline.org.br/abstract?id=sm11005&lang=en',
'http://www.bioline.org.br/abstract?id=sm11005&lang=fr',
'http://www.bioline.org.br/abstract?id=sm11006&lang=en',
'http://www.bioline.org.br/abstract?id=sm11006&lang=fr'
);

	foreach ($urls as $url)
	{

		$html = get($url);

		$reference = new stdclass;
		$reference->authors = array();
		//$reference->id = $id;


		$dom = str_get_html($html);

		$metas = $dom->find('meta');

		/*
		foreach ($metas as $meta)
		{
			echo $meta->name . " " . $meta->content . "\n";
		}
		*/				

		foreach ($metas as $meta)
		{
			switch ($meta->name)
			{

				// DC

				case 'DC.title':
				case 'dc.Title':
					$reference->title =  $meta->content;
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'DC.description':
				case 'DC.Description':
				case 'citation_abstract':
					$reference->abstract =  $meta->content;
					$reference->abstract = str_replace("\n", "", $reference->abstract);
					$reference->abstract = str_replace("&amp;", "&", $reference->abstract);
					$reference->abstract = preg_replace('/\s\s+/u', ' ', $reference->abstract);		
					$reference->abstract = preg_replace('/^\s+/u', '', $reference->abstract);		
					$reference->abstract = html_entity_decode($reference->abstract);
					break;
			
				case 'DC.Creator.PersonalName':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;	
			
				case 'DC.Source.ISSN':
					$reference->issn =  $meta->content;
					break;	

				// eprints

				case 'eprints.creators_name':
					$author = $meta->content;

					// clean
					if (preg_match('/^(?<lastname>.*),\s+(?<firstname>[A-Z][A-Z]+)$/u', $author, $m))
					{
						$parts = str_split($m['firstname']);
						$author = $m['lastname'] . ', ' . join(". ", $parts) . '.';
					}
					if (!in_array($author, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'eprints.publication':
					$reference->journal =  $meta->content;
					break;

				case 'eprints.issn':
					$reference->issn =  $meta->content;
					break;


				case 'eprints.volume':
					$reference->volume =  $meta->content;
					break;

				case 'eprints.pagerange':
					$pages =  $meta->content;
					$parts = explode("-", $pages);
					if (count($parts) > 1)
					{
						$reference->spage = $parts[0];
						$reference->epage = $parts[1];
					}
					else
					{
						$reference->spage = $pages;
					}
					break;

				case 'eprints.date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					break;

				case 'eprints.document_url':
					$reference->pdf =  urldecode($meta->content);
					break;

				// Google	
				case 'citation_authors':
					$authorstring = $meta->content;
					$authorstring = preg_replace('/,\s+/', '|', $authorstring);
					$authorstring = preg_replace('/\s+&\s+/', '|', $authorstring);
					$reference->authors = explode("|", $authorstring);
					break;
					
				case 'citation_author':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'citation_title':
					$reference->title = trim($meta->content);
					$reference->title = html_entity_decode($reference->title);
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'citation_doi':
					$reference->doi =  $meta->content;
					break;

				case 'citation_journal_title':
					$reference->journal =  $meta->content;
					$reference->genre = 'article';
					break;

				case 'citation_issn':
					if (!isset($reference->issn))
					{
						$reference->issn =  $meta->content;
					}
					break;

				case 'citation_volume':
					$reference->volume =  $meta->content;
					break;

				case 'citation_issue':
					$reference->issue =  $meta->content;
					break;

				case 'citation_firstpage':
					$reference->spage =  $meta->content;
			
					if (preg_match('/(?<spage>\d+)[-|-](?<epage>\d+)/u', $meta->content, $m))
					{
						$reference->spage =  $m['spage'];
						$reference->epage =  $m['epage'];
					}
					break;

				case 'citation_lastpage':
					$reference->epage =  $meta->content;
					break;

				case 'citation_abstract_html_url':
					$reference->url =  $meta->content;
					break;

				case 'citation_pdf_url':
					$reference->pdf =  $meta->content;
					break;
			
				case 'citation_fulltext_html_url':
					$reference->pdf =  $meta->content;
					//$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
					break;
			

				case 'citation_date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					break;

				case 'DC.Date':
				case 'dc.Date':
					$reference->date = $meta->content;
					break;


				default:
					break;
			}
		}		
		//print_r($reference);

		if ($reference->issn == '2413-3299')
		{
			unset($reference->doi);
			$reference->issn = '1815-8242';
		}				

		if (isset($reference->pdf))
		{
			$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
		}

		if (($year != '') && !isset($reference->year))
		{
			$reference->year = $year;
		}


		if (isset($reference->issue))
		{
			if ($reference->issue == 0)
			{
				unset($reference->issue);
			}
		}
		
		if (isset($reference->volume) && isset($reference->issue) )
		{
			if ($reference->volume == 0)
			{
				$reference->volume = $reference->issue;
				unset($reference->issue);
			}
		}
		

		// Dugesiana
		// Vol. 5, Núm. 2 (1998)
		if (preg_match('/Vol. \d+, Núm. \d+ \((?<year>[0-9]{4})\)/u', $html, $m))
		{
			$reference->year = $m['year'];
		}


		echo reference_to_ris($reference);

		// Give server a break every 10 items
		if (($count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}
	
}


?>