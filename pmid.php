<?php

// Map between DOI, PMID, and PMC

require_once(dirname(__FILE__) . '/lib.php');

//----------------------------------------------------------------------------------------
function doi_to_pmid($doi)
{
	$pmid = 0;
	$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=' . urlencode($doi . '[DOI]');
	
	//echo $url . "\n";
	
	$xml = get($url);
	
	//echo $xml;
	
	// Did we get a hit?
	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);
	
	$xpath_query = '//eSearchResult/Count';
	$nodeCollection = $xpath->query ($xpath_query);
	foreach($nodeCollection as $node)
	{
		$count = $node->firstChild->nodeValue;
	}
	
	if ($count == 1)
	{
		$xpath_query = '//eSearchResult/IdList/Id';
		$nodeCollection = $xpath->query ($xpath_query);
		foreach($nodeCollection as $node)
		{
			$pmid = $node->firstChild->nodeValue;
		}
	}
	
	return $pmid;
}

//----------------------------------------------------------------------------------------
function doi_to_pmc($doi)
{
	$pmc = 0;
	$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pmc&term=' . urlencode($doi . '[DOI]');
	
	//echo $url . "\n";
	
	$xml = get($url);
	
	//echo $xml;
	
	// Did we get a hit?
	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);
	
	$xpath_query = '//eSearchResult/Count';
	$nodeCollection = $xpath->query ($xpath_query);
	foreach($nodeCollection as $node)
	{
		$count = $node->firstChild->nodeValue;
	}
	
	if ($count == 1)
	{
		$xpath_query = '//eSearchResult/IdList/Id';
		$nodeCollection = $xpath->query ($xpath_query);
		foreach($nodeCollection as $node)
		{
			$pmc = $node->firstChild->nodeValue;
		}
	}
	
	return $pmc;
}

//----------------------------------------------------------------------------------------
function pmid_to_pmc($pmid)
{
	$pmc = 0;
	$url = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pmc&term=' . urlencode($pmid . '[pmid]');
	
	//echo $url . "\n";
	
	$xml = get($url);
	
	//echo $xml;
	
	// Did we get a hit?
	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);
	
	$xpath_query = '//eSearchResult/Count';
	$nodeCollection = $xpath->query ($xpath_query);
	foreach($nodeCollection as $node)
	{
		$count = $node->firstChild->nodeValue;
	}
	
	if ($count == 1)
	{
		$xpath_query = '//eSearchResult/IdList/Id';
		$nodeCollection = $xpath->query ($xpath_query);
		foreach($nodeCollection as $node)
		{
			$pmc = $node->firstChild->nodeValue;
		}
	}
	
	return $pmc;
}

$dois=array('10.11646/zootaxa.4216.1.3');

foreach ($dois as $doi)
{
	echo $doi . ' ' . doi_to_pmid($doi) . "\n";
}

$pmids = array(19266003);

foreach ($pmids as $pmid)
{
	echo $pmid . ' PMC' . pmid_to_pmc($pmid) . "\n";
}


