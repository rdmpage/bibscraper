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
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/10523",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/20004",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22207",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22208",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22209",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22210",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22211",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22212",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22213",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22214",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22215",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22216",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/22217",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/33002",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/39304",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/44615",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/47449",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/51702",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/53628",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/57329",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/60238",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/62812",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/67674",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8948",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8949",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8950",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8951",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8952",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8953",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8954",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8955",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8956",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8957",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8958",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8959",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8960",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8961",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8962",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8963",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8964",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8965",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8966",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8967",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8968",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8969",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8970",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8971",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8972",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8973",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8974",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8975",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8976",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8977",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8978",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8979",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8980",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8981",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8982",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8983",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8984",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8985",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8986",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8987",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8988",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8989",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8990",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8991",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8992",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8993",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8994",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8995",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8996",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8997",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8998",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/8999",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9000",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9001",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9002",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9003",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9004",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9005",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9006",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9007",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9008",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9009",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9010",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9011",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9012",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9013",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9014",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9015",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9016",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9017",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9018",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9019",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9020",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9021",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9022",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9023",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9024",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9025",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9026",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9027",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9028",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9029",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9030",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9031",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9032",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9033",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9034",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9035",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9036",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9037",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9038",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9039",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9040",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9041",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9042",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9043",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9044",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9045",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9046",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9047",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9048",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9049",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9050",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9051",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9052",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9053",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9054",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9055",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9056",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9057",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9058",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9059",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9060",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9061",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9062",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9063",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9064",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9065",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9066",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9067",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9068",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9069",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9070",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9071",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9072",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9073",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9074",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9075",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9076",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9077",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9078",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9079",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9080",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9081",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9082",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9083",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9084",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9085",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9086",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9087",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9088",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9089",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9090",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9091",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9092",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9093",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9094",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9095",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9096",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9097",
        "https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/9098");


//$issues=array('https://eprints.lib.hokudai.ac.jp/dspace/handle/2115/67674');

$count = 1;

foreach ($issues as $issue)
{
	$u = $issue;

//	$html = new_get($u);
	$html = get($u);
	
	
	$urls = array();
	$dom = str_get_html($html);
	
	//foreach ($dom->find('a[class=list-group-item]') as $a)
	foreach ($dom->find('td a') as $a)
	{
		if (preg_match('/https:\/\/eprints.lib.hokudai.ac.jp\/dspace\/handle\/\d+\/\d+$/', $a->href))
		{
			$urls[] = $a->href;
		}
		//echo $a->href . "\n";
	}
	
	print_r($urls);
	
	if (1)
	{
	foreach ($urls as $url)
	{
		//echo $url . "\n";
	
					$html = get($url);
					
					if ($html != '')
					{

				
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
				
					if (isset($reference->pdf))
					{
						$reference->url = $url;
						echo reference_to_ris($reference);
					}
					
					//exit();
				}
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