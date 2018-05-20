<?php

// revperu OAI harvester

require_once (dirname(__FILE__) . '/class_oai.php');
require_once (dirname(__FILE__) . '/lib.php');

class RevPeru extends OaiHarvester
{

	function Process()
	{
		$this->xml = str_replace("\n", '', $this->xml);
		$this->xml = preg_replace('/<OAI-PMH(.*)>/Uu', '<OAI-PMH>', $this->xml);
		
		//echo $this->xml;
		//echo "--------------------------------\n";

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
				//$reference->authors[] = mb_convert_case($n->firstChild->nodeValue, MB_CASE_TITLE, 'UTF-8');
				if (preg_match('/^(?<name>.*);/Uu', $n->firstChild->nodeValue, $m))
				{
					$reference->authors[] = $m['name'];
				}
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
				if (preg_match('/(?<journal>.*);\s+Vol.\s+(?<volume>\d+),\s+Núm.\s+(?<issue>\d+)\s+\((?<year>[0-9]{4})\)((:\s+(.*)))?;\s+(?<spage>\d+s?)\s*[-|-]\s*(?<epage>\d+s?)/u', $n->firstChild->nodeValue, $m))
				{
					$reference->journal = $m['journal'];
					$reference->volume = $m['volume'];
					$reference->issue = $m['issue'];
					$reference->spage = $m['spage'];
					$reference->epage = $m['epage'];
					$reference->year = $m['year'];
					
					$reference->spage = preg_replace('/^0+/', '', $reference->spage);
					$reference->epage = preg_replace('/^0+/', '', $reference->epage);
				}
				else
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
			
			// ISSN
			$reference->issn  = '1561-0837';
			$reference->eissn = '1727-9933';
			
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
			
			if (isset($reference->volume) && !isset($reference->spage))
			{
				$html = get($reference->url);
				
				if (preg_match('/<meta\s+name="citation_firstpage"\s+content="(?<spage>\d+)"\s*\/>/Uu', $html, $m))
				{
					$reference->spage = $m['spage'];
					$reference->spage = preg_replace('/^0+/', '', $reference->spage);
				}
				if (preg_match('/<meta\s+name="citation_lastpage"\s+content="(?<epage>\d+)"\s*\/>/Uu', $html, $m))
				{
					$reference->epage = $m['epage'];
					$reference->epage = preg_replace('/^0+/', '', $reference->epage);
				}
			}
	
	
			$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') VALUES (' . join(',', $values) . ');';
	
			echo $sql . "\n";
			
		


		}	
	}
	
	

}



$revperu = new RevPeru('http://revistasinvestigacion.unmsm.edu.pe/index.php/rpb/oai', 'oai_dc','rpb');

$revperu->harvest();


?>