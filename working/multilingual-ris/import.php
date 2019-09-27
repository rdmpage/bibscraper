<?php

// Import RIS file, detect language in some fields, and export in SQL
// Use RIS field "TT" to indicate alternative language title, as might be found
// in DC.title.alternative for example.

error_reporting(E_ALL);

require_once(dirname(dirname(dirname(__FILE__))) . '/ris.php');


require_once 'vendor/autoload.php';
use LanguageDetection\Language;


//----------------------------------------------------------------------------------------
function multilingual_import($reference)
{
	
	$reference->genre = 'article';
	
	//print_r($reference);
	
	$keys = array();
	$values = array();

	$multilingual_keys = array();
	$multilingual_values = array();
	
	
	if (isset($reference->alternativetitle))
	{
		$ld = new Language(['fr', 'en', 'de', 'es', 'pt', 'jp']);						
		$language = $ld->detect($reference->alternativetitle)->__toString();
		
		$language = 'jp';
		
		// multilingual
		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"title"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
			
		$kk[] = 'value';
		$vv[] = '"' . addcslashes($reference->alternativetitle, '"') . '"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;
	}
	
	if (isset($reference->title))
	{
		
		// do title	
		$ld = new Language(['en', 'de', 'es', 'pt']);						
		$language = $ld->detect($reference->title)->__toString();
		
		$language = 'en';
		
		// multilingual
		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"title"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
			
		$kk[] = 'value';
		$vv[] = '"' . addcslashes($reference->title, '"') . '"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;
		
	}
	
	
	if (isset($reference->alternativeauthors))
	{		
	
		$language = 'jp';
	
		// multilingual
		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"authors"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
		
		$kk[] = 'value';
		$vv[] = '"' . addcslashes(join(';', $reference->alternativeauthors), '"') . '"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;
		
		// then do authors 
		if (isset($reference->authors))
		{
			$language = 'en';
	
			// multilingual
			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"authors"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
		
			$kk[] = 'value';
			$vv[] = '"' . addcslashes(join(';', $reference->authors), '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;
		}		
	}

	

	
	// usual stuff
	foreach ($reference as $k => $v)
	{
		switch ($k)
		{
			// eat
			case 'genre':
			case 'alternativetitle':
			case 'alternativeauthors':
				break;
				
			case 'authors':
				$keys[] = $k;
				$values[] = '"' . addcslashes(join(';', $v), '"') . '"';
				break;
			
			case 'secondary_title':
				$keys[] = 'journal';
				$values[] = '"' . addcslashes($v, '"') . '"';			
				break;
				
			default:
				$keys[] = $k;
				$values[] = '"' . addcslashes($v, '"') . '"';
				break;
		}
	}
	
	/*
	print_r($keys);
	print_r($values);
	
	print_r($multilingual_keys);
	print_r($multilingual_values);	
	*/
	
	$guid = '';
	
	if ($guid == '')
	{
		if (isset($reference->doi))
		{
			$guid = $reference->doi;
		}
	}
	
	if ($guid == '')
	{
		if (isset($reference->url))
		{
			$guid = $reference->url;
		}
	}
	
	if ($guid == '')
	{
		$guid = md5(join('', $values));
	}	
	
	
	$keys[] = 'guid';
	$values[] = '"' . $guid . '"';
	
	$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
			. join(',', $values) . ');';

	echo $sql . "\n";

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


$filename = '';
if ($argc < 2)
{
	echo "Usage: import.php <RIS file> <mode>\n";
	exit(1);
}
else
{
	$filename = $argv[1];
}


$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

import_ris_file($filename, 'multilingual_import');



?>
