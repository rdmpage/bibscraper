<?php

// Gets metadata from <meta> tags
// harvest OAI

require_once (dirname(dirname(dirname(__FILE__))) . '/lib.php');
require_once (dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once (dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');


// get list of articles for each issue

$issues = array(
'http://search.informit.com.au/browsePublication;py=2006;vol=123;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2006;vol=123;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2006;vol=123;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2006;vol=123;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2006;vol=123;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2006;vol=123;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2007;vol=124;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2007;vol=124;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2007;vol=124;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2007;vol=124;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2007;vol=124;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2007;vol=124;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2008;vol=125;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2008;vol=125;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2008;vol=125;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2008;vol=125;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2008;vol=125;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2008;vol=125;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2009;vol=126;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2009;vol=126;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2009;vol=126;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2009;vol=126;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2009;vol=126;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2009;vol=126;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2010;vol=127;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2010;vol=127;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2010;vol=127;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2010;vol=127;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2010;vol=127;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2010;vol=127;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2011;vol=128;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2011;vol=128;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2011;vol=128;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2011;vol=128;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2011;vol=128;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2011;vol=128;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2012;vol=129;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2012;vol=129;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2012;vol=129;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2012;vol=129;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2012;vol=129;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2012;vol=129;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2013;vol=130;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2013;vol=130;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2013;vol=130;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2013;vol=130;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2013;vol=130;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2013;vol=130;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2015;vol=132;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2015;vol=132;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2015;vol=132;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2015;vol=132;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2015;vol=132;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2015;vol=132;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2016;vol=133;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2016;vol=133;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2016;vol=133;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2016;vol=133;res=IELHSS;issn=0042-5184;iss=4',
'http://search.informit.com.au/browsePublication;py=2016;vol=133;res=IELHSS;issn=0042-5184;iss=5',
'http://search.informit.com.au/browsePublication;py=2016;vol=133;res=IELHSS;issn=0042-5184;iss=6',
'http://search.informit.com.au/browsePublication;py=2017;vol=134;res=IELHSS;issn=0042-5184;iss=1',
'http://search.informit.com.au/browsePublication;py=2017;vol=134;res=IELHSS;issn=0042-5184;iss=2',
'http://search.informit.com.au/browsePublication;py=2017;vol=134;res=IELHSS;issn=0042-5184;iss=3',
'http://search.informit.com.au/browsePublication;py=2017;vol=134;res=IELHSS;issn=0042-5184;iss=4');

foreach ($issues as $issue)
{
	$u = $issue;

	$html = get($u);
	
	$urls = array();
		
	if (preg_match_all('/<a href="(?<url>\/documentSummary;dn=\d+;res=IELHSS)"/', $html, $m))
	{
		foreach ($m['url'] as $url)
		{
			$urls[] = 'http://search.informit.com.au' . $url;
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
			
				case 'DC.Date':
				case 'dc.Date':
				case 'citation_date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					
					if (preg_match('/\w+\s+(?<year>[0-9]{4})/', $meta->content, $m))
					{
						if (false != strtotime($meta->content))
						{
							$reference->date = date("Y/m//", strtotime($meta->content));
						}	
					}
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

		if (preg_match('/[0-9]{4}:\s+\d+-(?<epage>\d+)\.\s+Availability/u', $html, $m))
		{
			$reference->epage = $m['epage'];
		}

		if (preg_match('/,\s+The$/u', $reference->journal, $m))
		{
			$reference->journal = str_replace(', The', '', $reference->journal);
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