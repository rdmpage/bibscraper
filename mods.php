<?php

// Read metadata from MODs


$xml = file_get_contents('MODS Descriptive Metadata.xml');

//echo $xml;

	$reference = new stdclass;


	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);
	
	$xpath->registerNamespace('mods', "http://www.loc.gov/mods/v3");
	
	
	$nodeCollection = $xpath->query ('//mods:identifier');
	foreach($nodeCollection as $node)
	{
		echo $node->firstChild->nodeValue . "\n";
		
		if (preg_match('/http:\/\/hdl.handle.net\/(?<handle>.*)/', $node->firstChild->nodeValue , $m))
		{
			$reference->handle = $m['handle'];
		}
		
		if (preg_match('/ISSN\s+(?<issn>.*)/', $node->firstChild->nodeValue , $m))
		{
			$reference->issn = $m['issn'];
		}
		
	}
	
	
	$nodeCollection = $xpath->query ('//mods:name/mods:namePart');
	foreach($nodeCollection as $node)
	{
		$reference->authors[] = $node->firstChild->nodeValue;
	}
	
	$nodeCollection = $xpath->query ('//mods:physicalDescription/mods:extent');
	foreach($nodeCollection as $node)
	{
		if (preg_match('/(?<spage>\d+)-(?<epage>\d+)/',$node->firstChild->nodeValue,$m))
		{
			$reference->spage = $m['spage'];
			$reference->epage = $m['epage'];
		}
			
	}
	

	print_r($reference);




?>