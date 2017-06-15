<?php

// Gets metadata from <meta> tags
// harvest OAI

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

// get list of articles for each issue

$issues = array(
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/400',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/400',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/402',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/402',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/403',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/403',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/405',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/405',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/406',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/406',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/408',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/408',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/410',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/410',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/419',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/419',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/420',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/420',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/421',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/421',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/422',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/422',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/424',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/424',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/426',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/426',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/428',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/428',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/431',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/431',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/433',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/433',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/434',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/434',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/436',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/436',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/437',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/437',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/438',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/438',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/481',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/481',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/592',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/592',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/647',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/647',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/650',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/650',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/368',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/368',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/392',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/392',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/397',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/397',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/398',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/398',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/399',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/399',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/412',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/412',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/414',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/414',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/415',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/415',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/417',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/417',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/418',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/418');

//$issues=array('http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401');

//'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401/showToc'

foreach ($issues as $issue)
{
	$u = $issue . '/showToc';
	$html = get($u);
	
	$urls = array();
	
	if (preg_match_all('/<a href="(?<url>.*article\/view\/\d+)">/Uu', $html, $m))
	{
		foreach ($m['url'] as $url)
		{
			$urls[] = $url;
		}
	}

	$count = 1;


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
					$reference->title =  $meta->content;
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'DC.description':
				case 'DC.Description':
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
				case 'citation_author':
	//							$reference->authors[] =  mb_convert_case($meta->content, MB_CASE_TITLE);

					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'citation_title':
					$reference->title = trim($meta->content);
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
}


?>