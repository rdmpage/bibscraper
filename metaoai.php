<?php

// OAI harvester that gets metadata from <meta> tags

require_once (dirname(__FILE__) . '/class_oai.php');
require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

class MetaOai extends OaiHarvester
{

	function Process()
	{
		$this->xml = str_replace("\n", '', $this->xml);
		$this->xml = preg_replace('/<OAI-PMH(.*)>/Uu', '<OAI-PMH>', $this->xml);
		
		//echo $this->xml;
		
		//exit();

		$dom = new DOMDocument;
		$dom->loadXML($this->xml);
		$xpath = new DOMXPath($dom);
		
		$xpath->registerNamespace("dc", "http://purl.org/dc/elements/1.1/");	
		$xpath->registerNamespace("oaidc", "http://www.openarchives.org/OAI/2.0/oai_dc/");	
		$xpath->registerNamespace("xsi", "http://www.w3.org/2001/XMLSchema-instance");	
		
		$count = 1;
		
		
		$records = $xpath->query ('//ListRecords/record');
		foreach($records as $record)
		{
			//echo 'x';
		
			$url = '';
			$doi = '';
			$year = '';
		
			$id = '';
			
			$nodeCollection = $xpath->query ('header/identifier', $record);
			foreach($nodeCollection as $node)
			{
				$id = $node->firstChild->nodeValue;
			}
		
			$nodeCollection = $xpath->query ('metadata', $record);
			foreach($nodeCollection as $node)
			{
				// DOI and URLs
				$nc = $xpath->query ('oaidc:dc/dc:identifier', $node);
				foreach($nc as $n)
				{
					if (preg_match('/^10/', $n->firstChild->nodeValue))
					{
						$doi = $n->firstChild->nodeValue;
					}
					if (preg_match('/http:\/\/dx.doi.org\//', $n->firstChild->nodeValue))
					{
						$doi = $n->firstChild->nodeValue;
						$doi = str_replace('http://dx.doi.org/', '', $reference->doi);
					}
					if (preg_match('/view/', $n->firstChild->nodeValue))
					{
						$url = $n->firstChild->nodeValue;
					}
					
					if ($url == '')
					{					
						if (preg_match('/^https?/', $n->firstChild->nodeValue))
						{
							$url = $n->firstChild->nodeValue;
						}
					}					
					
				}
			
				// try and get year in case this is missing from metadata (sigh)
				$nc = $xpath->query ('oaidc:dc/dc:source', $node);
				foreach($nc as $n)
				{
					if (preg_match('/\((?<year>[0-9]{4})\)/', $n->firstChild->nodeValue, $m))
					{
						$year = $m['year'];
					}
				}
		
		
				if ($url != '')
				{
					echo $url . "\n";
					
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
								if (preg_match('/(?<volume>\d+):(?<spage>\d+)[-|-|–](?<epage>\d+)/u', $meta->content, $m))
								{
									$reference->volume = $m['volume'];
									$reference->spage = $m['spage'];
									$reference->epage = $m['epage'];
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
	}
	
	

}

//http://www.raco.cat/index.php/Mzoologica/issue/archive

//$mz = new MetaOai('http://www.raco.cat/index.php/Mzoologica/oai', 'oai_dc','Mzoologica');
//$mz->harvest();



//$mz = new MetaOai('http://journal.upao.edu.pe/Arnaldoa/oai', 'oai_dc','ARNALDOA');

//$mz = new MetaOai('http://www.revistascientificas.udg.mx/index.php/DUG/oai', 'oai_dc','Dugesiana');

//$mz = new MetaOai('http://www.tci-thaijo.org/index.php/ThaiForestBulletin/oai', 'oai_dc', 'ThaiForestBulletin');

//$mz = new MetaOai('https://biotaxa.org/rce/oai');

//$mz = new MetaOai('http://journal.upao.edu.pe/Arnaldoa/oai', 'oai_dc','ARNALDOA');


//$mz = new MetaOai('http://www.contributions-to-entomology.org/oai', 'oai_dc');


$mz = new MetaOai('https://deepblue.lib.umich.edu/dspace-oai/request', 'oai_dc', 'col_2027.42_41251');


$mz = new MetaOai('http://www.raco.cat/index.php/ButlletiICHN/oai', 'oai_dc');

$mz = new MetaOai('https://journal.lib.uoguelph.ca/index.php/eso/oai', 'oai_dc');

$mz = new MetaOai('https://scholarspace.manoa.hawaii.edu/dspace-oai/request', 'oai_dc', 'com_10125_25');




$mz->harvest();




?>