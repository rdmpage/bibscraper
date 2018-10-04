<?php

// Gets metadata from <meta> tags
// harvest OAI

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');


function new_get($url)
{
	$html = shell_exec('curl ' . $url);
	
	return $html;
}

$issues = array(
"https://scholarspace.manoa.hawaii.edu/handle/10125/7365",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15018",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15019",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15020",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15021",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15022",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15023",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15024",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15025",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15026",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15027",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15028",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15029",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15030",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15031",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15032",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15033",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15034",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15035",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15036",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15037",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14470",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14471",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14472",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15038",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15039",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15040",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15041",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15042",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15043",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15044",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15045",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15046",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15047",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15048",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15049",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15050",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15051",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15052",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15053",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15055",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15056",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15057",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15058",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15059",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15060",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15061",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15062",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14464",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14465",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14466",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14467",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14468",
"https://scholarspace.manoa.hawaii.edu/handle/10125/14469",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10644",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10645",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10646",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10647",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10648",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10649",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10650",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10651",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10652",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10653",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10654",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10655",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10656",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10657",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10658",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10659",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10660",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10661",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10662",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10663",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10664",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10665",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10666",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10667",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10668",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10669",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10670",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10671",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10672",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10673",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15063",
"https://scholarspace.manoa.hawaii.edu/handle/10125/15064",
"https://scholarspace.manoa.hawaii.edu/handle/10125/7369",
"https://scholarspace.manoa.hawaii.edu/handle/10125/7370",
"https://scholarspace.manoa.hawaii.edu/handle/10125/67",
"https://scholarspace.manoa.hawaii.edu/handle/10125/29",
"https://scholarspace.manoa.hawaii.edu/handle/10125/26",
"https://scholarspace.manoa.hawaii.edu/handle/10125/359",
"https://scholarspace.manoa.hawaii.edu/handle/10125/7371",
"https://scholarspace.manoa.hawaii.edu/handle/10125/10722",
"https://scholarspace.manoa.hawaii.edu/handle/10125/19910",
"https://scholarspace.manoa.hawaii.edu/handle/10125/21560",
"https://scholarspace.manoa.hawaii.edu/handle/10125/24434",
"https://scholarspace.manoa.hawaii.edu/handle/10125/30993",
"https://scholarspace.manoa.hawaii.edu/handle/10125/34430",
"https://scholarspace.manoa.hawaii.edu/handle/10125/38667",
"https://scholarspace.manoa.hawaii.edu/handle/10125/42696",
"https://scholarspace.manoa.hawaii.edu/handle/10125/48067",
"https://scholarspace.manoa.hawaii.edu/handle/10125/55857",
);

/*
$issues = array(
"https://scholarspace.manoa.hawaii.edu/handle/10125/10650",
);
*/

//$issues = array('https://scholarspace.manoa.hawaii.edu/handle/10125/359');

//$issues = array('https://scholarspace.manoa.hawaii.edu/handle/10125/15038');

$issues = array('https://scholarspace.manoa.hawaii.edu/handle/10125/10673');

$count = 1;

foreach ($issues as $issue)
{
	$u = $issue;
	
	$u .= '/browse?type=dateissued&year=-1&month=-1&sort_by=3&order=DESC&rpp=100&etal=0';

//	$html = new_get($u);
	$html = get($u);
	
	$urls = array();
	$dom = str_get_html($html);
	
	//foreach ($dom->find('a[class=list-group-item]') as $a)
	foreach ($dom->find('td[headers=t2] a') as $a)
	{
		if (preg_match('/\/handle\/\d+\/\d+$/', $a->href))
		{
			$urls[] = 'https://scholarspace.manoa.hawaii.edu' . $a->href;
		}
		//echo $a->href . "\n";
	}
	
	if (1)
	{
	foreach ($urls as $url)
	{
		//echo $url . "\n";
	
					$html = get($url);
				
					$reference = new stdclass;
					$reference->authors = array();
					
					$reference->oai = $id;
					
	
	
					$dom = str_get_html($html);

					$metas = $dom->find('meta');
				
					
					foreach ($metas as $meta)
					{
						//echo $meta->name . " " . $meta->content . "\n";
					}
					
						
					
					//exit();			
				
					foreach ($metas as $meta)
					{
						switch ($meta->name)
						{
	
							// DC
							
							case 'DC.relation':
								// hack
								if ($meta->content == 'Contributions')
								{
									$reference->journal = 'Contributions from the Museum of Paleontology University of Michigan';
									$reference->issn = '0097-3556';
								}
								
								if ($meta->content == 'Papers on Paleontology')
								{
									$reference->journal = 'Museum of Paleontology Papers on Paleontology';
									$reference->issn = '0148-3838';
								}
								
								
								
								break;
							
		
							case 'DC.title':
								$reference->title =  $meta->content;
								$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
								break;

							case 'DC.description':
							case 'DC.Description':
							case 'DCTERMS.abstract':
							
								if (preg_match('/(p.\s+)?(?<spage>\d+)-(?<epage>\d+)/',$meta->content, $m ))
								{
									$reference->spage = $m['spage'];
									$reference->epage = $m['epage'];
								}
								else
								{
									if (preg_match('/^http/', $meta->content))
									{
									}
									else
									{
										$reference->abstract =  $meta->content;
										$reference->abstract = str_replace("\n", "", $reference->abstract);
										$reference->abstract = str_replace("&amp;", "&", $reference->abstract);
										$reference->abstract = preg_replace('/\s\s+/u', ' ', $reference->abstract);		
										$reference->abstract = preg_replace('/^\s+/u', '', $reference->abstract);		
										$reference->abstract = html_entity_decode($reference->abstract);
									}
								}
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
								
							// to do
							case 'DC.Identifier.DOI':
								break;
								
							case 'DCTERMS.bibliographicCitation':
								$reference->notes = $meta->content;
							
							
								//echo $meta->content . "\n";
								if (preg_match('/^Vol.\s+(?<volume>\d+)(,\s+No.\s+(?<issue>\d+))?/', $meta->content, $m))
								{
									$reference->volume = $m['volume'];
									if ($m['issue'] != '')
									{
										$reference->issue = $m['issue'];
									}
								}
								if (preg_match('/^No.\s+(?<volume>\d+)/', $meta->content, $m))
								{
									$reference->volume = $m['volume'];
								}
								
								
								// <meta name="DCTERMS.bibliographicCitation" content="Proceedings of the Hawaiian Entomological Society (2016) 48:39-50." xml:lang="en_US">
								if (preg_match('/(?<volume>\d+)(\((?<issue>\d+)\))?:\s*(?<spage>\d+)([-|-|–](?<epage>\d+))?/u', $meta->content, $m))
								{
									$reference->volume = $m['volume'];
									
									$reference->volume = preg_replace('/^0+/', '', $reference->volume);
									
									if ($m['issue'] != '')
									{
										$reference->issue = $m['issue'];
										$reference->issue = preg_replace('/^0+/', '', $reference->issue);
									}
									
									$reference->spage = $m['spage'];
									$reference->spage = preg_replace('/^0+/', '', $reference->spage);
									
									if ($m['epage'] != '')
									{
										$reference->epage = $m['epage'];
										$reference->epage = preg_replace('/^0+/', '', $reference->epage);
									}
											
									
								}
								if (preg_match('/(?<journal>Pro.* So\w+)\s+/u', $meta->content, $m))
								{
									$reference->journal = 'Proceedings of the Hawaiian Entomological Society';
								}
								
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
							
								// echo $meta->content . "\n";
							
								if (preg_match('/^[0-9]{4}$/', $meta->content))
								{
									$reference->year = $meta->content;
								}
			
								if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
								{
									$reference->year = $m['year'];
								}
								
								if (preg_match('/^(?<year>[0-9]{4})\/\d+\/\d+/u', $meta->content, $m))
								{
									$reference->date = str_replace('/', '-', $meta->content);
								}
								if (preg_match('/^(?<year>[0-9]{4})-\d+-\d+/u', $meta->content, $m))
								{
									$reference->date = $meta->content;
								}
								if (preg_match('/^(?<year>[0-9]{4})-\d+/u', $meta->content, $m))
								{
									$reference->date = $meta->content . '-00';
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
				
					// handle volume="0"
					if (isset($reference->volume))
					{
						if ($reference->volume == 0)
						{
							if (isset($reference->issue))
							{
								if ($reference->issue != 0)
								{
									$reference->volume = $reference->issue;
									unset($reference->issue);
								}
							}
						}
					}
				
				
					if (isset($reference->issue))
					{
						if ($reference->issue == 0)
						{
							unset($reference->issue);
						}
					}
					
					// grab anything missing from HTML
					
					$as = $dom->find('a');
				
					
					foreach ($as as $a)
					{
						//echo $a->plaintext . "\n";
						if (preg_match('/(?<volume>\d+)\s+:\s+[0-9]{4}/', $a->plaintext, $m))
						{
							//print_r($m);
							$reference->volume = $m['volume'];
						}
						// 1908: 8,
						if (preg_match('/[0-9]{4}\s*:\s+(?<volume>\d+),/', $a->plaintext, $m))
						{
							//print_r($m);
							$reference->volume = $m['volume'];
						}
					}
					
					if (isset($reference->issn))
					{
						$reference->issn = str_replace('ISSN ', '', $reference->issn);
						
					}
					
					
					
					// print_r($reference);
				
					echo reference_to_ris($reference);
					
					//exit();
				}
			
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