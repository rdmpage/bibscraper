<?php

/**
 * @file endnote.php
 *
 */

// Parse EndNote format from Zoological Records and try and find first page of article in BHL

require_once (dirname(__FILE__) . '/nameparse.php');

$debug = true;

$key_map = array(
	'UT' => 'publisher_id',
	'TI' => 'title',
	'SN' => 'issn',
	'SO' => 'secondary_title',
	'VL' => 'volume',
	'IS' => 'issue',
	'BP' => 'spage',
	'EP' => 'epage',
	'AB' => 'abstract',
	'PY' => 'year',
	'DI' => 'doi'
	);
	
//--------------------------------------------------------------------------------------------------
function process_endnote_key($key, $value, &$obj)
{
	global $key_map;
	global $debug;
	
	//echo $key . "\n";
	
	switch ($key)
	{
		case 'AU':
			// Ignore as we handle this in main loop
			break;
	
		case 'SO':
			
			/*
			$value = mb_convert_case($value, 
				MB_CASE_TITLE, mb_detect_encoding($value));
				
			$value = preg_replace('/ Of /', ' of ', $value);
			$value = preg_replace('/ The /', ' the ', $value);
			*/
			
			$obj->$key_map[$key] = $value;
			
			if ($obj->$key_map[$key] == 'POSTILLA [YALE PEABODY MUS]')
			{
				$obj->$key_map[$key] = 'Postilla';
			}
			break;
			
		case 'SU':
			// SU No. 44
			if (preg_match('/No. (?<volume>\d+)$/', $value, $m))
			{
				$obj->volume = $m['volume'];
			}
			
			break;
			
		case 'FT':
			$value = rtrim($value, '.');
			$obj->title = $value;
			break;
			
		case 'TI':
			$value = str_replace("“", "\"", $value);
			$value = str_replace("”", "\"", $value);
			$value = str_replace("Afri-can", "African", $value);
			$value = rtrim($value, '.');
			
			
			if (0)
			{
				$value = mb_convert_case($value, 
					MB_CASE_TITLE, mb_detect_encoding($value));
					
				$value = str_replace(' A ', ' a ', $value);
				$value = str_replace(' Of ', ' of ', $value);
				$value = str_replace(' The ', ' the ', $value);
				$value = str_replace(' By ', ' by ', $value);
				$value = str_replace(' And ', ' and ', $value);
				$value = str_replace(' In ', ' in ', $value);
				$value = str_replace(' From ', ' from ', $value);
				$value = str_replace(' With ', ' with ', $value);
				$value = str_replace(' Are ', ' are ', $value);
				$value = str_replace(' On ', ' on ', $value);
				$value = str_replace(' For ', ' for ', $value);
				$value = str_replace(' New ', ' new ', $value);
		
				// ZooRecord does wierd things to taxon names
				if (preg_match_all('/(?<genus>[A-Z]\w+)-(?<species>[A-Z]\w+)\b/Uu', $value, $m))
				{
					//print_r($m);
					
					for($i=0;$i<count($m[0]);$i++)
					{
						$value = str_replace($m[0][$i], $m['genus'][$i] . ' ' 
							. mb_convert_case($m['species'][$i], 
								MB_CASE_LOWER, mb_detect_encoding($value)),
							$value);
					}
				}
			}			
						
			$obj->$key_map[$key] = $value;
			break;
			
		case 'PS':
			$value = preg_replace('/^pp. /', '', $value);
			$parts = explode('-', $value);
			$obj->spage = $parts[0];
			$obj->spage = str_replace('(', '', $obj->spage);
			$obj->spage = str_replace(')', '', $obj->spage);
			$obj->spage = str_replace('[', '', $obj->spage);
			$obj->spage = str_replace(']', '', $obj->spage);
			$obj->epage = $parts[1];
			$obj->epage = str_replace('(', '', $obj->epage);
			$obj->epage = str_replace('(', '', $obj->epage);
			$obj->epage = str_replace('[', '', $obj->epage);
			$obj->epage = str_replace(']', '', $obj->epage);
			break;	
			
		case 'BP':
			$value = trim($value);
			if (preg_match('/\((?<spage>\d+)/', $value, $m))
			{
				$value = $m['spage'];
			}
			$obj->$key_map[$key] = $value;
			break;

		case 'EP':
			$value = trim($value);
			if (preg_match('/(?<epage>\d+)\)/', $value, $m))
			{
				$value = $m['epage'];
			}
			$obj->$key_map[$key] = $value;
			break;
			
		default:
			if (array_key_exists($key, $key_map))
			{
				// Only set value if it is not empty
				if ($value != '')
				{
					$obj->$key_map[$key] = $value;
				}
			}
			break;
	}
}

//--------------------------------------------------------------------------------------------------
function add_author(&$obj, $authorstring)
{	
	$matched = false;
	
	if (!$matched)
	{
		if (preg_match('/^(?<lastname>\w+),?\s(?<forename>.*)(\s(?<suffix>Jr))?$/Uu', $authorstring, $m))
		{
			$a = new stdClass();
			$a->forename = $m['forename'];
			$a->surname = $m['lastname'];
			
			if ($m['suffix'] != '')
			{
				$a->suffix = $m['suffix'];
			}
						
			
			if (preg_match('/^([A-Z]+)$/Uu', $a->forename))
			{
				$str = $a->forename;			
				
				$len = mb_strlen($str);
				
				if ($len < 3)
				{
					$parts = array();
					for($i=0;$i<$len;$i++)
					{
						$parts[] = mb_substr($str,$i,1);
					}
					$a->forename = join(" ", $parts);
				}	
				
			}		
			
			//echo $a->forename . "\n";
			
			
			// Keep as string
			//$author = $a->forename . ' ' . $a->surname;
			$author = $a->surname . ', ' . $a->forename;
			
		}
		
		$matched = true;
	}
		
	if (!$matched)
	{
		if (preg_match('/^(?<lastname>\w+([-| ]\w+)?),\s*(?<forename>.*)$/Uu', $authorstring, $m))
		{
			$a = new stdClass();
			$a->forename = $m['forename'];
			$a->surname = $m['lastname'];
			
			// Keep as string
			//$author = $a->forename . '' . $a->surname;
			$author = $a->surname . ',' . $a->forename;
		}			
		
		$matched = true;
	}
	
	if (!$matched)
	{
		$author = $authorstring;
	}
	
	//echo "matched=$matched\n";
	//echo "author=$author\n";
	//echo "authorstring=$authorstring\n";
	
	/*
	$author = preg_replace('/\.([A-Z])/u', ". $1", $author);
	$author = preg_replace('/^\s+/u', "", $author);

	
	$author = mb_convert_case($author, 
			MB_CASE_TITLE, mb_detect_encoding($author));
	*/
	$author = str_replace(".", " ", $author);
	$author = preg_replace('/\s\s+/', ' ', $author);
	
	
	$author = mb_convert_case($author, 
			MB_CASE_TITLE, mb_detect_encoding($author));
	
	
	array_push($obj->authors, $author);
}


//--------------------------------------------------------------------------------------------------
// Use this function to handle very large RIS files
function import_endnote_file($filename, $callback_func = '')
{
	global $debug;
	
	$file_handle = fopen($filename, "r");
			
	$state = 1;	
	
	$current_key = '';
	$current_value = '';
	
	while (!feof($file_handle)) 
	{
		$line = fgets($file_handle);
		
		//echo $line . "\n";
		
		if (preg_match('/^(?<key>[A-Z]{2})\s(?<value>.*)$/Uu', $line, $m))
		{
			//print_r($m);
			$current_key 	= $m['key'];
			$current_value 	= $m['value'];
			
			if ($current_key == 'AU')
			{
				//echo $current_value . "\n";
				
				//$current_value = mb_strtolower($current_value, mb_detect_encoding($current_value));
				
				add_author($obj, $current_value);
			}
			
		}
		if (preg_match('/^ER$/Uu', $line))
		{
			$current_key = 'ER';
		}
		if (preg_match('/^   (?<value>["|\(]?\w+(.*))$/Uu', $line, $m))
		{
			//echo "current_key=$current_key" . $m['value'] . "\n";
			if ($current_key == 'AU')
			{
				$current_value 	= $m['value'];
				//echo $current_value . "\n";
				//$current_value = mb_strtolower($current_value, mb_detect_encoding($current_value));
				
				add_author($obj, $current_value);
			}
			else
			{
				$current_value .= ' ' . $m['value'];
			}
		}
				
		if ($current_key == 'PT')
		{
			$state = 1;
			$obj = new stdClass();
			$obj->authors = array();
			
			if ('J' == $value)
			{
				$obj->genre = 'article';
			}
		}
		if ($current_key == 'ER')
		{
			$state = 0;
			
			if (isset($obj->issue))
			{
				$obj->issue = preg_replace('/pt\.\s+/', '', $obj->issue);
				$obj->issue = preg_replace('/^\(/', '', $obj->issue);
				$obj->issue = preg_replace('/\)$/', '', $obj->issue);
			}

			if (isset($obj->spage))
			{
				$obj->spage = preg_replace('/^\(/', '', $obj->spage);
			}
			if (isset($obj->epage))
			{
				$obj->epage = preg_replace('/\)$/', '', $obj->epage);
				$obj->epage = preg_replace('/^p\.\s+/', '', $obj->epage);
			}
						
			// Cleaning...						
			if (0)
			{
				print_r($obj);
			}	
			
			if ($callback_func != '')
			{
				$callback_func($obj);
			}
			
			$current_key = '';
			$current_value = '';
			
		}
		
		if ($state == 1)
		{
			if ($current_value != '')
			{
				process_endnote_key($current_key, $current_value, $obj);
			}
		}
	}
	
	
}

// test

//import_endnote_file('ZooRecord/savedrecs.txt');


?>