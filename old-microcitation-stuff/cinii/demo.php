<?php

require_once('../lib.php');

// CiNii

//----------------------------------------------------------------------------------------
function add_multilingual_kv($key, $value, &$multilingual_keys, &$multilingual_values)
{
	$language = 'en';
	if (preg_match('/\p{Han}+/u', $value))
	{
		$language = 'jp';
	}

	$kk = array();
	$vv = array();
	$kk[] = "`key`";
	$vv[] = '"' . $key . '"';

	$kk[] = 'language';
	$vv[] = '"' . $language . '"';
			
	$kk[] = 'value';
	$vv[] = '"' . addcslashes($value, '"') . '"';

	$multilingual_keys[] = $kk;
	$multilingual_values[] = $vv;
}

//----------------------------------------------------------------------------------------
// Parse CiNii RDF and handle multilingual info
function parse_rdf($xml, $pdf = '')
{
	$dom= new DOMDocument;
	$dom->loadXML($xml);
	$xpath = new DOMXPath($dom);
	
	$xpath->registerNamespace ('rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
	$xpath->registerNamespace ('dc', "http://purl.org/dc/elements/1.1/");
	$xpath->registerNamespace ('dcterms', "http://purl.org/dc/terms/");
	$xpath->registerNamespace ('foaf', "http://xmlns.com/foaf/0.1/");
	$xpath->registerNamespace ('prism', "http://prismstandard.org/namespaces/basic/2.0/");
	$xpath->registerNamespace ('bibo', "http://xmlns.com/foaf/0.1/");
	$xpath->registerNamespace ('foaf', "http://purl.org/ontology/bibo/");
			
	// Identifier
	$q = $xpath->query ('//rdf:Description[1]/@rdf:about');
	foreach($q as $n)
	{
		$url = $n->firstChild->nodeValue;
		$url = str_replace('#article', '', $url);
		$guid = $url;
	}
		
	// default language
	$default_language = 'jp';
	$q = $xpath->query ('//rdf:Description/dc:language');
	foreach($q as $n)
	{
		//echo $n->firstChild->nodeValue . "\n"; exit();
		switch ($n->firstChild->nodeValue)
		{
			case 'JPN':
				$default_language = 'jp';
				break;
			case 'ENG':
				$default_language = 'en';
				break;
			default:
				break;
		}
	}
	
	$authors = array();
		
	// Japanese
	$jp = $xpath->query ('//rdf:Description[1]');
	foreach($jp as $node)
	{			
		$nc = $xpath->query ('dc:creator', $node);
		foreach ($nc as $n)
		{
			$authors[] = $n->firstChild->nodeValue;
		}
	
		// title
		$nc = $xpath->query ('dc:title', $node);
		foreach ($nc as $n)
		{
			add_multilingual_kv('title', $n->firstChild->nodeValue, $multilingual_keys, $multilingual_values);	
			
			if ($default_language == 'jp')
			{
				$keys[] = 'title';
				$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
			}			
		}
		
		// abstract
		$nc = $xpath->query ('dc:description', $node);
		foreach ($nc as $n)
		{					
			add_multilingual_kv('abstract', $n->firstChild->nodeValue, $multilingual_keys, $multilingual_values);	
			
			if ($default_language == 'jp')
			{
				$keys[] = 'abstract';
				$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
			}			
						
		}
		
		// journal
		$nc = $xpath->query ('prism:publicationName', $node);
		foreach ($nc as $n)
		{
			add_multilingual_kv('journal', $n->firstChild->nodeValue, $multilingual_keys, $multilingual_values);				
			if ($default_language == 'jp')
			{
				$keys[] = 'journal';
				$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
			}			
		}
		
		$nc = $xpath->query ('prism:issn', $node);
		foreach ($nc as $n)
		{
			$keys[] = 'issn';
			$issn = substr($n->firstChild->nodeValue, 0, 4) . '-' . substr($n->firstChild->nodeValue, 4, 4);
			$values[] = '"' . addcslashes($issn, '"') . '"';
		}				
		
		
		// prism:volume
		$nc = $xpath->query ('prism:volume', $node);
		foreach ($nc as $n)
		{
			$keys[] = 'volume';
			$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
		}				
		$nc = $xpath->query ('prism:number', $node);
		foreach ($nc as $n)
		{
			$keys[] = 'issue';
			$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
		}				
		$nc = $xpath->query ('prism:startingPage', $node);
		foreach ($nc as $n)
		{
			$keys[] = 'spage';
			$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
		}				
		$nc = $xpath->query ('prism:endingPage', $node);
		foreach ($nc as $n)
		{
			$keys[] = 'epage';
			$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
		}	
		
		// date
		$nc = $xpath->query ('dc:date', $node);
		foreach ($nc as $n)
		{
			$keys[] = 'date';
			$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';

			$keys[] = 'year';
			$values[] = '"' . addcslashes(substr($n->firstChild->nodeValue, 0, 4), '"') . '"';

		}				
					
		
	}
	
	if (count($authors) > 0)
	{
		add_multilingual_kv('authors', join(";", $authors), $multilingual_keys, $multilingual_values);	
		if ($default_language == 'jp')
		{
			$keys[] = 'authors';
			$values[] = '"' . addcslashes(join(";", $authors), '"') . '"';
		}			
	}
	$authors = array();

	// English
	$en = $xpath->query ('//rdf:Description[@xml:lang="en"]');
	foreach($en as $node)
	{
	
		$nc = $xpath->query ('dc:creator', $node);
		foreach ($nc as $n)
		{
			$authors[] = $n->firstChild->nodeValue;
		}
	
		// title
		$nc = $xpath->query ('dc:title', $node);
		foreach ($nc as $n)
		{
			add_multilingual_kv('title', $n->firstChild->nodeValue, $multilingual_keys, $multilingual_values);				
			
			if ($default_language == 'en')
			{
				$keys[] = 'title';
				$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
			}			
			
		}
		
		// abstract
		$nc = $xpath->query ('dc:description', $node);
		foreach ($nc as $n)
		{					
			add_multilingual_kv('abstract', $n->firstChild->nodeValue, $multilingual_keys, $multilingual_values);	
			if ($default_language == 'en')
			{
				$keys[] = 'abstract';
				$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
			}			
						
		}
		
		// journal
		$nc = $xpath->query ('prism:publicationName', $node);
		foreach ($nc as $n)
		{
			add_multilingual_kv('journal', $n->firstChild->nodeValue, $multilingual_keys, $multilingual_values);				
			if ($default_language == 'en')
			{
				$keys[] = 'journal';
				$values[] = '"' . addcslashes($n->firstChild->nodeValue, '"') . '"';
			}			
		}
		
		
	}
	
	if (count($authors) > 0)
	{
		add_multilingual_kv('authors', join(";", $authors), $multilingual_keys, $multilingual_values);
		
		if ($default_language == 'en')
		{
			$keys[] = 'authors';
			$values[] = '"' . addcslashes(join(";", $authors), '"') . '"';
		}			
			
	}

	if ($pdf != '')
	{
		$keys[] = 'pdf';
		$values[] = '"' . $pdf . '"';
	}

	$keys[] = 'url';
	$values[] = '"' . $url . '"';


	$keys[] = 'guid';
	$values[] = '"' . $guid . '"';
		
	/*
	print_r($keys);
	print_r($values);

	print_r($multilingual_keys);
	print_r($multilingual_values);
	*/
	
	{
		// populate from scratch
		$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
			. join(',', $values) . ');';
		echo $sql . "\n";
	}	
	
	$n = count($multilingual_keys);
	for($i =0; $i < $n; $i++)
	{
		$multilingual_keys[$i][] = 'guid';
		$multilingual_values[$i][] = '"' . $guid . '"';

		$sql = 'REPLACE INTO multilingual(' . join(',', $multilingual_keys[$i]) . ') values('
			. join(',', $multilingual_values[$i]) . ');';
		echo $sql . "\n";
	}
}		
		

//$xml = file_get_contents('110004697577.xml');

//parse_rdf($xml);

$urls = array('http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344862_en.html');

$urls = array(
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344838_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344838_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344839_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344839_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344841_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344841_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344842_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344842_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344843_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344843_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344845_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344845_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344846_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344846_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344848_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344848_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344849_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344849_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344850_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344850_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344852_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344852_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344853_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344853_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344855_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344855_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344856_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344856_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344858_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344858_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344860_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344860_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344861_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344861_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344862_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344862_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344863_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344863_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344865_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344865_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344867_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000344867_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000345030_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000345030_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000345707_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000345707_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000393226_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000393226_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000436799_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN10009042/ISS0000436799_en.html'
);

$urls = array(
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220238_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220238_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220239_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220239_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220240_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220240_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220241_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000220241_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000403659_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000403659_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404323_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404323_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404324_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404324_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404325_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404325_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404326_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404326_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404327_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404327_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404328_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404328_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404330_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404330_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404348_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404348_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404349_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404349_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404350_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404350_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404351_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000404351_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000415050_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000415050_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000421163_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000421163_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000424541_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000424541_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000430486_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000430486_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000437791_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000437791_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000443253_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000443253_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000449537_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000449537_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000454086_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000454086_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000466671_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000466671_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000475782_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000475782_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000481091_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000481091_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000485140_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000485140_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000488748_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000488748_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000489042_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000489042_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000490720_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000490720_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000493634_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000493634_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000495295_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000495295_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000502394_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000502394_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000504338_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000504338_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000505409_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000505409_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000511451_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000511451_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000511452_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000511452_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000513029_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000513029_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000514503_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11586265/ISS0000514503_en.html'
);

$urls=array(
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223288_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223288_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223289_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223289_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223290_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223290_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223291_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223291_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223292_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223292_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223293_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223293_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223294_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223294_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223295_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223295_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223296_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223296_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223297_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223297_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223298_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223298_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223299_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223299_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223300_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223300_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223301_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223301_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223302_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223302_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223303_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223303_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223304_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223304_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223305_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223305_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223306_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223306_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223307_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223307_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223308_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223308_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223309_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223309_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223310_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223310_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223311_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223311_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223312_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223312_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223313_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223313_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223314_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223314_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223315_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223315_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223316_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223316_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223317_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223317_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223318_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223318_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223319_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223319_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223320_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223320_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223321_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223321_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223322_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223322_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223323_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223323_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223324_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223324_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223325_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223325_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223326_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223326_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223327_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223327_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223328_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223328_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223329_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223329_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223330_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223330_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223331_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223331_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223332_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223332_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223333_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223333_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223334_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223334_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223335_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223335_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223336_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223336_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223337_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223337_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223338_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223338_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223339_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223339_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223340_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223340_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223342_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223342_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223344_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223344_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223346_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223346_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223349_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223349_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223350_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223350_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223353_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223353_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223355_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223355_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223357_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223357_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223359_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223359_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223361_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223361_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223363_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223363_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223366_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223366_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223368_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223368_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223370_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223370_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223372_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223372_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223374_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223374_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223376_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223376_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223378_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223378_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223381_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223381_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223383_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223383_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223384_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223384_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223385_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223385_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223387_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223387_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223388_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223388_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223389_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223389_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223391_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223391_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223392_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223392_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223393_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223393_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223394_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223394_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223395_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223395_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223396_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223396_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223398_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223398_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223399_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223399_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223401_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223401_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223402_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223402_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223404_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223404_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223406_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223406_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223408_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223408_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223409_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223409_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223411_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223411_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223412_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223412_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223413_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223413_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223415_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223415_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223417_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223417_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223419_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223419_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223420_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223420_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223421_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223421_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223423_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223423_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223424_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223424_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223426_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223426_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223427_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223427_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223429_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223429_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223430_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223430_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223431_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223431_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223433_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223433_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223435_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223435_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223437_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223437_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223438_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223438_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223440_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223440_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223442_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223442_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223444_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223444_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223446_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223446_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223448_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223448_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223450_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223450_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223451_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223451_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223453_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223453_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223454_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223454_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223455_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223455_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223456_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223456_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223457_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223457_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223458_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223458_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223459_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223459_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223460_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223460_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223461_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223461_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223462_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223462_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223463_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223463_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223465_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223465_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223467_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223467_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223469_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223469_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223471_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223471_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223475_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223475_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223476_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223476_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223481_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223481_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223484_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223484_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223485_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223485_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223488_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223488_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223489_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223489_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223491_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223491_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223492_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223492_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223494_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223494_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223500_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223500_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223501_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223501_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223503_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223503_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223504_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223504_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223506_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223506_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223509_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000223509_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000404334_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AN00118019/ISS0000404334_en.html'
);

$urls = array(
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000220235_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000220235_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000220236_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000220236_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000220237_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000220237_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407906_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407906_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407907_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407907_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407908_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407908_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407909_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407909_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407910_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407910_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407911_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407911_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407912_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407912_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407913_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000407913_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000408011_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000408011_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000409060_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000409060_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000420432_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000420432_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000424589_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000424589_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000434212_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000434212_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000439162_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000439162_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000448679_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000448679_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000454084_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000454084_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000466670_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000466670_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000473042_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000473042_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000478754_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000478754_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000482690_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000482690_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000488747_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000488747_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000493632_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000493632_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000503178_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000503178_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000507072_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000507072_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000511453_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000511453_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000514487_en.html',
'http://ci.nii.ac.jp/vol_issue/nels/AA11585830/ISS0000514487_en.html'
);

$count = 0;

foreach ($urls as $url)
{
	$html = get($url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');

	//echo $html;

	if (preg_match_all('/<TR>\s*<TD bgColor=(.*)>\s*<A\s+href="(?<url>\/naid\/\d+\/en)">(?<title>.*)<\/A>&nbsp;\s*<span(.*)<\/span>(.*)\s*<a href="(?<pdf>.*)">CiNii<\/a>(.*)/Uui', $html, $m))
	{
		//print_r($m);
	
		$n = count($m[0]);
		for ($i = 0; $i < $n; $i++)
		{
			$url = $m['url'][$i];
			$url = 'http://ci.nii.ac.jp' . $url;
			$url = str_replace('/en', '', $url);	
			$url .= '.rdf';	
	
			$pdf = 'http://ci.nii.ac.jp' . $m['pdf'][$i];
				
			$xml = get($url);
			parse_rdf($xml, $pdf);
			
			if (($count++ % 10) == 0)
			{
				$rand = rand(1000000, 3000000);
				echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
				usleep($rand);
			}			
		}
	
	}
}
			
?>