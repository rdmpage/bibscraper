<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/lib.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');

$basedir = dirname(__FILE__) . '/html';

$files = scandir($basedir);

// debugging
//$files=array('19-1-morphologie-et-variations-du-toit-cranien-du-dipneuste-scaumenacia-curta-whiteaves-sarcopterygii-du-devonien-superieur-du-quebec.html');

$pdfs = array();


foreach ($files as $filename)
{
	// echo "filename=$filename\n";
	
	if (preg_match('/\.html$/', $filename))
	{	
		$html = file_get_contents($basedir . '/' . $filename);
		
		$dom = str_get_html($html);
		
		$metas = $dom->find('meta');
		
		$reference = new stdclass;
		$reference->authors = array();
		$reference->issn = '1280-9659';
				
		foreach ($metas as $meta)
		{
			switch ($meta->name)
			{

				// DC
				case 'DC.title':
					$reference->title =  $meta->content;
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				/*
				case 'description':
				case 'DC.description':
				case 'DC.Description':
					$reference->abstract =  $meta->content;
					$reference->abstract = str_replace("\n", "", $reference->abstract);
					$reference->abstract = str_replace("&amp;", "&", $reference->abstract);
					$reference->abstract = preg_replace('/\s\s+/u', ' ', $reference->abstract);		
					$reference->abstract = html_entity_decode($reference->abstract);
					break;
				*/
					
				case 'DC.Creator.PersonalName':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;	
					
				case 'DC.Source.ISSN':
					$reference->issn =  $meta->content;
					break;	



				// Google (and weird variations on)	
				case 'bepress_citation_author':
				case 'citation_author':
					$author = $meta->content;
					$author =  mb_convert_case($author, MB_CASE_TITLE);

					if (!in_array($author, $reference->authors))
					{
						$reference->authors[] =  $author;
					}
					break;

				case 'bepress_citation_title':
				case 'citation_title':
					$reference->title = trim($meta->content);
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'bepress_citation_doi':
				case 'citation_doi':
					$reference->doi =  $meta->content;
					break;

				case 'bepress_citation_journal_title':
				case 'citation_journal_title':
					$reference->journal =  $meta->content;
					$reference->genre = 'article';
					break;

				case 'bepress_citation_issn':
				case 'citation_issn':
					if (!isset($reference->issn))
					{
						$reference->issn =  $meta->content;
					}
					break;

				case 'bepress_citation_volume':
				case 'citation_volume':
					$reference->volume =  $meta->content;
					break;

				case 'bepress_citation_issue':
				case 'citation_issue':
					$reference->issue =  $meta->content;
					break;

				case 'bepress_citation_firstpage':
				case 'citation_firstpage':
					$reference->spage =  $meta->content;
					
					if (preg_match('/(?<spage>\d+)[-|-](?<epage>\d+)/u', $meta->content, $m))
					{
						$reference->spage =  $m['spage'];
						$reference->epage =  $m['epage'];
					}
					break;

				case 'bepress_citation_lastpage':
				case 'citation_lastpage':
					$reference->epage =  $meta->content;
					break;

				case 'citation_abstract_html_url':
					$reference->url =  $meta->content;
					break;

				case 'bepress_citation_pdf_url':
				case 'citation_pdf_url':
					$reference->pdf =  $meta->content;
					break;
					
				case 'citation_fulltext_html_url':
					$reference->pdf =  $meta->content;
					//$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
					break;
					

				case 'bepress_citation_date':
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
		
		// PDF
		$as = $dom->find('a');
		foreach ($as as $a)
		{
			if (preg_match('/\.pdf$/', $a->href))
			{
				$reference->pdf = 'http://sciencepress.mnhn.fr' . $a->href;
				
				$pdfs[] = $reference->pdf;
			}
			
		}

		$as = $dom->find('a[title=Permalink]');
		foreach ($as as $a)
		{
			$reference->url = $a->href;
			
		}
		
		
		
		//print_r($reference);
		
		echo reference_to_ris($reference);
		
	}

}

echo join("\n", $pdfs) . "\n";
		
?>

