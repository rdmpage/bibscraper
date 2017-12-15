<?php

// Lookup in biostor
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/ris.php');
require_once(dirname(__FILE__) . '/utils.php');

$count = 1;

//----------------------------------------------------------------------------------------
// Set $store to true if we want to get BoStor to add this reference (may slow things down)
function import_from_openurl($openurl, $threshold = 0.5, $store = true)
{
	$found = 0;
	
	// filter
	
	// 2. Call BioStor
	$url = 'http://direct.biostor.org/openurl.php?' . $openurl . '&format=json';
	$json = get($url);
	
	//echo "-- $url\n";
	
	
	
	
	
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
	//echo "Found $found\n";
	
	if ($found == 0)
	{
		echo '<span style="color:white;background-color:red;">Not found: <a href="http://direct.biostor.org/openurl?' . $openurl . '" target="_new">OpenURL</a>' . '</span><br />';
	
	}
	else
	{
		echo 'Found: <a href="http://direct.biostor.org/reference/' . $found . '" target="_new">' . $found . '</a>' . '<br />';
	}
	
	//exit();
	
	return $found;
}

//----------------------------------------------------------------------------------------


$ids = array();
$not_found = array();

$list_of_matches = array();

function biostor_import($reference)
{
	global $ids;
	global $not_found;
	global $list_of_matches;
	
	$reference->genre = 'article';
	
	// Ignore things we don't have
	//if ($reference->year != 1991) return;
	//if (!in_array($reference->volume, array(38,39,40,41))) return;
	
	//if (!in_array($reference->volume, array(57,58,59))) return;
	
	//if (!in_array($reference->volume, array(42,43,44))) return;
	
	// Ignore BioStor stuff
	$ignore = false;
	if (isset($reference->url))
	{
		if (preg_match('/biostor/', $reference->url))
		{
			$ignore = true;
		}
	}
	if ($ignore) { return; }
	
	// Tropicos
	$reference->title =  preg_replace('/~/Uu', '', $reference->title);	
	$reference->title =  preg_replace('/---/Uu', ' ', $reference->title);	
	

	//print_r($reference);
	
	//exit();
	
	
	echo '<h2>' . $reference->title . '</h2>';
	
	
	
	
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
	
	$go = true;
	
	/*
	// filter for Contributions in Science
	$go = false;
	//$go = true;
	//if (($reference->issn == '0459-8113') && ($reference->volume >= 64) && ($reference->volume <= 85))
//	if (($reference->issn == '0459-8113') && ($reference->volume >= 35) && ($reference->volume <= 63))
//	if (($reference->issn == '0459-8113') && ($reference->volume >= 86) && ($reference->volume <= 91))
	if (($reference->issn == '0459-8113') && ($reference->volume >= 507) && ($reference->volume <= 515)) // 121

	{
//		$reference->spage = 1;
		if ($reference->spage == 1)
		{
			$reference->notes += 2;
		}
		$go = true;
	}
	else
	{
		echo "*** NO GO ***\n";
	}
	
	*/
	if ($go)
	{
	

	$openurl = reference2openurl($reference);
	
	
	

	
	
	// BHL -fudge PageID in Notes field
	if (isset($reference->notes) && is_numeric($reference->notes))
	{
		$openurl .= '&id=http://biodiversitylibrary.org/page/' . $reference->notes;
	}
	
	
	if (0)
	{
		echo "-- " . $openurl . "\n";
		echo "-- " . $reference->title . "\n";	
	}
	
	//exit();
	
	$biostor_id = import_from_openurl($openurl, 0.5, true);
				
	if ($biostor_id == 0)
	{
		// echo "-- *** Not found ***\n";
		$not_found[] = $reference->publisher_id;
	}
	else
	{
		// echo "-- Found: $biostor_id\n";
		
		
		
		
		
		$ids[] = $biostor_id;
		
		if (0)
		{
			if (preg_match('/http:\/\/www.jstor.org\/stable\/(?<jstor>\d+)/', $reference->url, $m))
			{
				$sql = 'UPDATE rdmp_reference SET jstor=' . $m['jstor'] . ' WHERE reference_id="' . $biostor_id . '";';
				echo $sql . "\n";
			}
		}	
		
		if (1)
		{
			$list_of_matches[$reference->publisher_id] = $biostor_id;
		}
		
	}
	
	}
	//exit();
	
	if (($count++ % 5) == 0)
	{
		$rand = rand(100000, 1000000);
		//echo '...sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
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

import_ris_file($filename, 'biostor_import');

/*
print_r($ids);
echo "Not found\n";
print_r($not_found);

echo "Matched\n";
foreach ($list_of_matches as $k => $v)
{
	echo "k\t$v\n";
}
*/

?>