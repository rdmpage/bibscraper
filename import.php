<?php

// Lookup in biostor
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/ris.php');
require_once(dirname(__FILE__) . '/utils.php');

//----------------------------------------------------------------------------------------
// Set $store to true if we want to get BoStor to add this reference (may slow things down)
function import_from_openurl($openurl, $threshold = 0.5, $store = true)
{
	$found = 0;
	
	// 2. Call BioStor
	$url = 'http://direct.biostor.org/openurl.php?' . $openurl . '&format=json';
	$json = get($url);
	
	//echo $url . "\n";
	
	//echo $json;
		
	// 3. Search result
		
	$x = json_decode($json);
	
	//print_r($x);
	//exit();
	
	if (isset($x->reference_id))
	{
		// 4. We have this already
		$found = $x->reference_id;
	}
	else
	{
		// 5. Did we get a (significant) hit? 
		// Note that we may get multiple hits, we use the best one
		$h = -1;
		$n = count($x);
		for($k=0;$k<$n;$k++)
		{
			if ($x[$k]->score > $threshold)
			{
				$h = $k;
			}
		}
		
		if (($h != -1) && $store)
		{		
			// 6. We have a hit, construct OpenURL that forces BioStor to save
			$openurl .= '&id=http://www.biodiversitylibrary.org/page/' . $x[$h]->PageID;
			$url = 'http://direct.biostor.org/openurl.php?' . $openurl . '&format=json';

			$json = get($url);
			$j = json_decode($json);
			$found = $j->reference_id;
		}
	}
	echo "Found $found\n";
	
	//exit();
	
	return $found;
}

//----------------------------------------------------------------------------------------


$ids = array();
$not_found = array();

function biostor_import($reference)
{
	global $ids;
	global $not_found;
	
	$reference->genre = 'article';
	
	// Ignore things we don't have
	//if ($reference->year > 1922) return;
	// if (!in_array($reference->volume, array(71,74,77))) return;
	
	// Tropicos
	$reference->title =  preg_replace('/~/Uu', '', $reference->title);	
	$reference->title =  preg_replace('/---/Uu', ' ', $reference->title);	
	

	//print_r($reference);
	
	//exit();
	
	
	
	// clean
	if (isset($reference->issn))
	{
		if ($reference->issn == '0193-4406')
		{
			if (isset($reference->issue))
			{
				if (isset($reference->volume))
				{
					if ($reference->volume == 0)
					{
						$reference->volume = $reference->issue;
						unset($reference->issue);
					}
				}
				else
				{
					$reference->volume = $reference->issue;
					unset($reference->issue);
				}
				
				 //print_r($reference);
				 //exit();
			}
		}
	}
	
	
	

	$openurl = reference2openurl($reference);
	
	

	
	
	// BHL -fudge PageID in Notes field
	if (isset($reference->notes) && is_numeric($reference->notes))
	{
		$openurl .= '&id=http://biodiversitylibrary.org/page/' . $reference->notes;
	}
	
	
	echo "-- " . $openurl . "\n";
	echo "-- " . $reference->title . "\n";	
	
	//exit();
	
	$biostor_id = import_from_openurl($openurl, 0.5, true);
				
	if ($biostor_id == 0)
	{
		echo "-- *** Not found ***\n";
		$not_found[] = $reference->publisher_id;
	}
	else
	{
		echo "-- Found: $biostor_id\n";
		$ids[] = $biostor_id;
		
		if (1)
		{
			if (preg_match('/http:\/\/www.jstor.org\/stable\/(?<jstor>\d+)/', $reference->url, $m))
			{
				$sql = 'UPDATE rdmp_reference SET jstor=' . $m['jstor'] . ' WHERE reference_id="' . $biostor_id . '";';
				echo $sql . "\n";
			}
		}			
		
	}
	
	
	//exit();
	
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

import_ris_file($filename, 'biostor_import');

print_r($ids);
echo "Not found\n";
print_r($not_found);


?>