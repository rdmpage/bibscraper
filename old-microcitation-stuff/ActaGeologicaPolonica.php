<?php

// Studia Geologica Salmanticensia OAI harvester

require_once (dirname(__FILE__) . '/class_oai.php');

class AGP extends OaiHarvester
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
		
		$nodeCollection = $xpath->query ('//ListRecords/record/metadata');
		foreach($nodeCollection as $node)
		{
			$reference = new stdclass;
			$reference->authors = array();
		
			/*
			$nc = $xpath->query ('oaidc:dc/dc:description[2]', $node);
			foreach($nc as $n)
			{
				$str = $n->firstChild->nodeValue;
				if (preg_match('/^p. (?<spage>\d+)-(?<epage>\d+)/', $str, $m))
				{ 
					$reference->spage = $m['spage'];
					$reference->epage = $m['epage'];
				}
			}
			*/

			// title
			$nc = $xpath->query ('oaidc:dc/dc:title', $node);
			foreach($nc as $n)
			{
				$reference->title = $n->firstChild->nodeValue;
			}
			
			$nc = $xpath->query ('oaidc:dc/dc:creator', $node);
			foreach($nc as $n)
			{
				$author = mb_convert_case($n->firstChild->nodeValue, MB_CASE_TITLE, 'UTF-8');
				$author = preg_replace('/;(.*)$/Uu', '', $author);
				$reference->authors[] = $author;
			}

			$nc = $xpath->query ('oaidc:dc/dc:description[1]', $node);
			foreach($nc as $n)
			{
				$reference->abstract = $n->firstChild->nodeValue;
			}
			
			/*
			$nc = $xpath->query ('oaidc:dc/dc:date[1]', $node);
			foreach($nc as $n)
			{
				$reference->date = $n->firstChild->nodeValue;
				if (preg_match('/^(?<year>[0-9]{4})/', $n->firstChild->nodeValue, $m))
				{
					$reference->year = $m['year'];
				}
			}
			*/
			
			$nc = $xpath->query ('oaidc:dc/dc:source[1]', $node);
			foreach($nc as $n)
			{
				$matched = false;
				
				
				if (!$matched)
				{
					// // Acta Geologica Polonica; Vol 60, No 4 (2010); 445–477
					// Acta Geologica Polonica; Vol 60, No 1 (2010): Advances in marine ichnology; 53–70
					if (preg_match('/(?<journal>.*); Vol\s*(?<volume>\d+), No (?<issue>\d+([-|–]\d+)?)\s+\((?<year>[0-9]{4})\)(:\s+.*)?;\s+(?<spage>\d+)[-|–](?<epage>\d+)$/Uu', $n->firstChild->nodeValue, $m))
					{
						$reference->journal = $m['journal'];
						$reference->volume = $m['volume'];
						$reference->issue = $m['issue'];
						$reference->spage = $m['spage'];
						$reference->epage = $m['epage'];
						$reference->year = $m['year'];
						
						$matched = true;
					}
				}

				if (!$matched)
				{
					// Acta Geologica Polonica; Vol 20, No 3 (1970)
					if (preg_match('/(?<journal>.*); Vol\s*(?<volume>\d+), No (?<issue>\d+([-|–]\d+)?)\s+\((?<year>[0-9]{4})\)$/Uu', $n->firstChild->nodeValue, $m))
					{
						$reference->journal = $m['journal'];
						$reference->volume = $m['volume'];
						$reference->issue = $m['issue'];
						$reference->year = $m['year'];
						
						$matched = true;
					}
				}
				
				
				if (!$matched)
				{
					// Studia Geologica Salmanticensia; Vol. 27 (1991)
					if (preg_match('/(?<journal>.*); Vol.\s*(?<volume>\d+)\s+\((?<year>[0-9]{4})\)$/Uu', $n->firstChild->nodeValue, $m))
					{
						$reference->journal = $m['journal'];
						$reference->volume = $m['volume'];
						//$reference->spage = $m['spage'];
						//$reference->epage = $m['epage'];
						$reference->year = $m['year'];
						
						$matched = true;
					}
				}
				
				// Studia Geologica Salmanticensia; Vol. 44, núm. 2 (2008); 213-220
				if (!$matched)
				{
					if (preg_match('/(?<journal>.*); Vol.\s*(?<volume>\d+),(\s+\w+\.?\s+(?<issue>\d+))?\s+\((?<year>[0-9]{4})\);\s+(?<spage>\d+)-(?<epage>\d+)$/Uu', $n->firstChild->nodeValue, $m))
					{
						$reference->journal = $m['journal'];
						$reference->volume = $m['volume'];
						$reference->spage = $m['spage'];
						$reference->epage = $m['epage'];
						$reference->year = $m['year'];
						
						$matched = true;
					}
				}
				
				// Studia Geologica Salmanticensia; Vol. 22 (1986): Cuenca del Duero; 37-59
				if (!$matched)
				{
					if (preg_match('/(?<journal>.*); Vol.\s*(?<volume>\d+)\s+\((?<year>[0-9]{4})\):(\s+\w+)+;\s+(?<spage>\d+)-(?<epage>\d+)$/Uu', $n->firstChild->nodeValue, $m))
					{
						$reference->journal = $m['journal'];
						$reference->volume = $m['volume'];
						$reference->spage = $m['spage'];
						$reference->epage = $m['epage'];
						$reference->year = $m['year'];
						
						$matched = true;
					}
				}
				
				// Studia Geologica Salmanticensia; Vol. 42 (2006); 61-66
				if (!$matched)
				{
					if (preg_match('/(?<journal>.*); Vol.\s*(?<volume>\d+)\s+\((?<year>[0-9]{4})\);\s+(?<spage>\d+)-(?<epage>\d+)$/Uu', $n->firstChild->nodeValue, $m))
					{
						$reference->journal = $m['journal'];
						$reference->volume = $m['volume'];
						$reference->spage = $m['spage'];
						$reference->epage = $m['epage'];
						$reference->year = $m['year'];
						
						$matched = true;
					}
				}
				

								
				if (!$matched)
				{
					$reference->journal = $n->firstChild->nodeValue;
				}
			}
			
			
			
			// DOI and URLs
			$nc = $xpath->query ('oaidc:dc/dc:identifier', $node);
			foreach($nc as $n)
			{
				if (preg_match('/^10/', $n->firstChild->nodeValue))
				{
					$reference->doi = $n->firstChild->nodeValue;
				}
				if (preg_match('/download/', $n->firstChild->nodeValue))
				{
					$reference->pdf = $n->firstChild->nodeValue;
				}
				if (preg_match('/view/', $n->firstChild->nodeValue))
				{
					$reference->url = $n->firstChild->nodeValue;
				}
			}
			
			// PDF
			$nc = $xpath->query ('oaidc:dc/dc:relation', $node);
			foreach($nc as $n)
			{
				$reference->pdf = str_replace('/view/', '/download/', $n->firstChild->nodeValue);
			}
			
			
			// http://cascavel.ufsm.br/revistas/ojs-2.2.2/index.php/balduinia/article/view/14039/8772
			// http://cascavel.ufsm.br/revistas/ojs-2.2.2/index.php/balduinia/article/download/14039/8772
			
			// ISSN
			$reference->issn = '0001-5709';
			
			//print_r($reference);
			
			$keys = array();
			$values = array();
	
			$keys[] = 'guid';
			
			if (isset($reference->doi))
			{
				$guid = $reference->doi;
			}
			else
			{
				$guid = $reference->url;
			}
			
			$values[] = "'" . $guid . "'";
	
			foreach ($reference as $k => $v)
			{
				switch ($k)
				{
				
					case 'authors':
						$keys[] = 'authors';
						$authors = array();
						foreach ($reference->authors as $au)
						{
							$authors[] = $au;
						}
						$values[] = "'" . addcslashes(join(";", $authors), "'") . "'";
						break;
				
					default:
						$keys[] = $k;
						$values[] = "'" . addcslashes($v, "'") . "'";
						break;
				}
			}
	
	
			$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') VALUES (' . join(',', $values) . ');';
	
			echo $sql . "\n";
			
		


		}	
	}
	
	

}



$harvester = new AGP('https://geojournals.pgi.gov.pl/agp/oai', 'oai_dc','agp:ART');

$harvester->harvest();


?>