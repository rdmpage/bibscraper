<?php

/**
 * @file other_endnote.php
 *
 */

// Parse EndNote format from BHLL

require_once (dirname(__FILE__) . '/nameparse.php');
require_once (dirname(__FILE__) . '/utils.php');

$debug = true;

$key_map = array(
	
	'%D' => 'date',
	'%A' => 'author',
	'%T' => 'title',
	'%J' => 'secondary_title',
	'%V' => 'volume',
	'%N' => 'issue',
	//'%&' => 'issue',
	'%P' => 'pages',
	'%U' => 'url',
	
	'%R' => 'doi'
	
	);

/*	
%0 Journal Article
%A Ashe,J.
%D 1956
%T Observations on Günter's Garter or coral snake, Elapsoidea Sundevalii Quintheri Loveridge
%J Journal of The East Africa Natural History Society and National Museum
%V XXV
%N 2
%P 122-124
%U http://www.biodiversitylibrary.org/part/140628
*/	
	
//--------------------------------------------------------------------------------------------------
function process_endnote_key($key, $value, &$obj)
{
	global $key_map;
	global $debug;
	
	//echo $key . "\n";
	//echo $value . "\n";
	
	switch ($key)
	{
				
		case '%P':
			$parts = preg_split('/-[-]?/', $value);
			$obj->spage = $parts[0];
			$obj->epage = $parts[1];
			
			$obj->spage	= preg_replace('/^0+/', '', $obj->spage);		
			$obj->epage	= preg_replace('/^0+/', '', $obj->epage);	
			
			if ($obj->epage == '')
			{
				unset($obj->epage);
			}				
			break;	
		
		case '%A':
			$value = preg_replace('/\s\s+/', "|", $value);
			$value = preg_replace('/,/', ", ", $value);
			$obj->authors = explode("|", $value);
			break;
			
		case '%Z':
			$obj->notes = $value;
			// %Z Author(s) - Stewart RJ.Miura T.
			$value = preg_replace('/Author\(s\)\s+-\s+/', "", $value);
			$value = preg_replace('/([A-Z])([A-Z])/', "$1 $2", $value);
			
			if (preg_match('/Anonymous/', $value))
			{
			
			}
			else
			{
				if (preg_match('/,/' , $value))
				{
					$obj->authors = preg_split('/,\s*/',  $value);
				}
				else
				{
					$value = preg_replace('/\s+/', ", ", $value);
					$obj->authors = preg_split('/\./',  $value);
				}				
			}
			break;
					
		case '%V':
			if (is_numeric($value))
			{
				$obj->volume = $value;
			}
			else
			{
				$obj->volume = arabic($value);
			}
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
		$line = trim(fgets($file_handle));
		
		//echo $line . "\n";
		
		if (preg_match('/^(?<key>\%[A-Z0-9])\s(?<value>.*)$/Uu', $line, $m))
		{
			//print_r($m);
			$current_key 	= $m['key'];
			$current_value 	= $m['value'];
		}
		
		//echo "Current key=$current_key\n";

				
		if ($current_key == '%0')
		{
			$state = 1;
			$obj = new stdClass;
			$obj->authors = array();
			
			$obj->genre = 'article';
			
		}
		if ($current_key == '%U')
		{
			if ($current_value != '')
			{
				process_endnote_key($current_key, $current_value, $obj);
			}
					
			$state = 0;
						
			// Cleaning...	
			
			$obj->title = preg_replace('/\[Review\]/i', '', $obj->title);
			$obj->title = preg_replace('/\[\d+ refs\]/i', '', $obj->title);
			$obj->title = preg_replace('/\s+$/', '', $obj->title);
			$obj->title = preg_replace('/\.$/', '', $obj->title);
								
			if (1)
			{
				//print_r($obj);
				//echo reference_to_ris($obj);
				
				//echo ".";
			}	
			
			if ($obj->secondary_title == 'Journal of the American Mosquito Control Association')
			{
				//print_r($obj);
				
				$obj->issn = '8756-971X';
				
				//echo reference_to_ris($obj);	
				
				
				if (preg_match("/^(?<year>[0-9]{4})\-(?<month>[0-9]{2})\-(?<day>[0-9]{2})$/", $obj->date, $matches))
				{
					echo 'UPDATE publications SET date="' . $obj->date . '" WHERE guid="' . $obj->url . '";' . "\n";
					echo 'UPDATE publications SET year="' . $matches['year'] . '" WHERE guid="' . $obj->url . '";' . "\n";
				}
				else
				{
					echo 'UPDATE publications SET year="' . $obj->date . '" WHERE guid="' . $obj->url . '";' . "\n";
					$ris .= "Y1  - " . $v . "\n";
				}		
				
				
						
				//exit();
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

//import_endnote_file('j.enw');
import_endnote_file('www.biodiversitylibrary.org/data/bhlpart.enw');


?>