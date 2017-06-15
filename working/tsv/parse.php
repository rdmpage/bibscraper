<?php

// Parse a TSV file and extract references


require_once(dirname(__FILE__) . '/crossref.php');
require_once(dirname(__FILE__) . '/lcs.php');

require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');

//----------------------------------------------------------------------------------------
function biostor_openurl (&$reference)
{
	if (isset($reference->journal) && isset($reference->volume) && isset($reference->spage))
	{
		print_r($reference);
		
		$openurl = reference2openurl($reference);

		$url = 'http://direct.biostor.org/openurl.php?' . $openurl . '&format=json';
		$json = get($url);
		
		$obj = json_decode($json);
		
		if (isset($obj->reference_id))
		{
			$reference->biostor = $obj->reference_id;
			
			if (isset($obj->doi))
			{			
				$reference->doi = $obj->doi;
			}		
		}
		else
		{
			echo str_replace('&format=json', '', $url) . "\n";
		}
		//print_r($obj);
	}
}	

//----------------------------------------------------------------------------------------
function search($citation)
{
	$result = crossref_search($citation);
	
	//print_r($result);
	
	$double_check = true;
	$theshhold = 0.8;
	
	if ($double_check)
	{
		// get metadata 
		$query = explode('&', html_entity_decode($result->coins));
		$params = array();
		foreach( $query as $param )
		{
		  list($key, $value) = explode('=', $param);
		  
		  $key = preg_replace('/^\?/', '', urldecode($key));
		  $value = strip_tags(urldecode($value));
		  $params[$key][] = trim($value);
		}
		
		//print_r($params);
		
		$hit = '';
		
		/*
		if (isset($params['rft.au']))
		{
			$hit = join(",", $params['rft.au']);
		}
		*/
		
		$hit = $params['rft.date'][0];
		  
		$hit .= ' ' . $params['rft.atitle'][0] 
			. '. ' . $params['rft.jtitle'][0]
			. ' ' . $params['rft.volume'][0]
			. ': ' .  $params['rft.spage'][0];

		$v1 = $citation;
		$v2 = $hit;
		
		//echo "-- $hit\n";
		
		

		$v1 = finger_print($v1);
		$v2 = finger_print($v2);	
		
		//echo "v1: $v1\n";
		//echo "v2: $v2\n";
						

		if (($v1 != '') && ($v2 != ''))
		{
			//echo "v1: $v1\n";
			//echo "v2: $v2\n";

			$lcs = new LongestCommonSequence($v1, $v2);
			$d = $lcs->score();

			// echo $d;

			$score = min($d / strlen($v1), $d / strlen($v2));

			//echo "score=$score\n";
			
			if ($score > $theshhold)
			{
			
			}
			else
			{
				unset ($result);
			}
		}
	}
	
	return $result;
}


$filename = 'Reptile_bibliography2014-12.tsv';
$filename = 'test.txt';

$journals_to_skip = array();


$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$row = trim(fgets($file_handle));
		
	$parts = explode("\t",$row);
	
	//print_r($parts);
	
	
	$keys = array(
		'id' => 0, 
		'author' => 1, 
		'year' => 2, 
		'title' => 3, 
		'source' => 4,
		'url' => 5);
	
	
	$reference = new stdclass;
	$reference->publisher_id = $parts[$keys['id']];
	
	if ($parts[$keys['year']] != '')
	{
		$reference->year = $parts[$keys['year']];
	}

	if ($parts[$keys['title']] != '')
	{
		$reference->title = $parts[$keys['title']];
		$reference->title = str_replace("\n", " ", $reference->title);
		$reference->title = str_replace("\r", " ", $reference->title);
	}
	
	$matched = false;
	
	if (!$matched)
	{
		if (preg_match('/(?<journal>.*)[,]?\s+(?<volume>\d+)(\s*\((?<issue>.*)\))?[:]\s*(?<spage>\d+)\./Uu', $parts[$keys['source']], $m))
		{
			//print_r($m);
			
			$reference->genre = 'article';
		
			$reference->journal = $m['journal'];	
			$reference->volume = $m['volume'];	
			
			if ($m['issue'] != '')
			{
				$reference->issue = $m['issue'];	
			}
			if ($m['spage'] != '')
			{
				$reference->spage = $m['spage'];	
			}
		
			$matched = true;
		
		}
	}	
	
	if (!$matched)
	{
		if (preg_match('/(?<journal>.*)[,]?\s+(?<volume>\d+)(\s*\((?<issue>.*)\))?([:]\s*(?<spage>\d+)\s*[-|-|–]\s*(?<epage>\d+))\b/Uu', $parts[$keys['source']], $m))
		{
			//print_r($m);
			
			$reference->genre = 'article';
		
			$reference->journal = $m['journal'];	
			$reference->volume = $m['volume'];	
			
			if ($m['issue'] != '')
			{
				$reference->issue = $m['issue'];	
			}
			if ($m['spage'] != '')
			{
				$reference->spage = $m['spage'];	
			}
			if ($m['epage'] != '')
			{
				$reference->epage = $m['epage'];	
			}
		
			$matched = true;
		
		}
	}
		
	if (preg_match('/(?<doi>10\.\d+\/.*)\b/i', $parts[$keys['url']], $m))
	{
		$reference->doi = $m['doi'];
		
		// clean 
		
		$reference->doi = preg_replace('/\/abstract(.*)/i', '', $reference->doi);
		$reference->doi = preg_replace('/\/pdf(.*)/i', '', $reference->doi);
		$reference->doi = preg_replace('/\/assert(.*)/i', '', $reference->doi);
		$reference->doi = preg_replace('/\/suppinfo(.*)/i', '', $reference->doi);
		
		
		
		$reference->doi = preg_replace('/#(.*)/i', '', $reference->doi);
		$reference->doi = preg_replace('/\?(.*)/i', '', $reference->doi);
		$reference->doi = preg_replace('/&(.*)/i', '', $reference->doi);
		$reference->doi = urldecode($reference->doi);
		
	}
	
	array_shift($parts);
	
	$reference->notes = join(" ", $parts);
	
	
	if (1)
	{
		biostor_openurl($reference);
	}
	
	if (0)
	{
		if (!isset($reference->doi))
		{
			if (isset($reference->journal) && isset($reference->volume))
			{
				//attempt to have some simpe rules for what journals to skip
				if (!in_array($reference->journal, $journals_to_skip))
				{
		
		
					$query_term = '';
			
					//$query_term = $row;
			
					$query_term = join(" ", array($reference->year, $reference->title,  $reference->journal,  $reference->volume,  $reference->spage));
			
					$result = search($query_term);
	
					if ($result)
					{
						$reference->doi = $result->doi;
					}
					else
					{
						if (!in_array($reference->journal, array('Zootaxa')))
						{
							echo '-- skip ' . $reference->journal . "\n";
							$journals_to_skip[] = $reference->journal;
						}
					}				
				}
			}
		}	
	}

	
	if (0)
	{
		echo reference_to_ris($reference);
	}
	
	if (1)
	{
		if (isset($reference->doi))
		{
			echo "UPDATE `bibliography` SET doi='" . $reference->doi . "' WHERE id='" . $reference->publisher_id . "';" . "\n";
		}
		if (isset($reference->biostor))
		{
			echo "UPDATE `bibliography` SET biostor='" . $reference->biostor . "' WHERE id='" . $reference->publisher_id . "';" . "\n";
		}
		
	}
	

	
}

//echo '</table>';

?>
