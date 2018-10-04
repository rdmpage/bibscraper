<?php

require_once (dirname(__FILE__) . '/fingerprint.php');
require_once (dirname(__FILE__) . '/nameparse.php');

//--------------------------------------------------------------------------------------------------
// From http://snipplr.com/view/6314/roman-numerals/
// Expand subtractive notation in Roman numerals.
function roman_expand($roman)
{
	$roman = str_replace("CM", "DCCCC", $roman);
	$roman = str_replace("CD", "CCCC", $roman);
	$roman = str_replace("XC", "LXXXX", $roman);
	$roman = str_replace("XL", "XXXX", $roman);
	$roman = str_replace("IX", "VIIII", $roman);
	$roman = str_replace("IV", "IIII", $roman);
	return $roman;
}
    
//--------------------------------------------------------------------------------------------------
// From http://snipplr.com/view/6314/roman-numerals/
// Convert Roman number into Arabic
function arabic($roman)
{
	$result = 0;
	
	$roman = strtoupper($roman);

	// Remove subtractive notation.
	$roman = roman_expand($roman);

	// Calculate for each numeral.
	$result += substr_count($roman, 'M') * 1000;
	$result += substr_count($roman, 'D') * 500;
	$result += substr_count($roman, 'C') * 100;
	$result += substr_count($roman, 'L') * 50;
	$result += substr_count($roman, 'X') * 10;
	$result += substr_count($roman, 'V') * 5;
	$result += substr_count($roman, 'I');
	return $result;
} 

//----------------------------------------------------------------------------------------
function pause()
{
	$rand = rand(1000000, 3000000);
	usleep($rand);
}

//----------------------------------------------------------------------------------------
function authors_from_string($authorstring, $split_on_commas = false)
{
	$authors = array();
	
	$authorstring = preg_replace("/\s+&amp;\s+/u", "|", $authorstring);
	 
	
	// Strip out suffix
	$authorstring = preg_replace("/,\s*Jr./u", "", trim($authorstring));
	$authorstring = preg_replace("/,\s*jr./u", "", trim($authorstring));
	
	$authorstring = preg_replace("/\.\-/Uu", "-", $authorstring);	
	
	/*
	//echo $authorstring . "\n";
	if (preg_match('/^(?<name>\w+((\s+\w+)+)?),/u', $authorstring, $m))
	{
		//print_r($m);
		$authorstring = preg_replace("/,/u", "|", $authorstring);
		//$authorstring = preg_replace("/^" . $m['name'] . "\|/u", $m['name'] . ",", $authorstring);
		//echo $authorstring . "\n";
	}
	else
	{
		$authorstring = preg_replace("/,/u", "|", trim($authorstring));
	}
    //echo $authorstring . "\n";
    */
    
    if ($split_on_commas)
    {
    	$authorstring = preg_replace("/,\s*/u", "|", trim($authorstring));
    }


	$authorstring = preg_replace("/,$/u", "", trim($authorstring));
	$authorstring = preg_replace("/&/u", "|", $authorstring);
	$authorstring = preg_replace("/;/u", "|", $authorstring);
	$authorstring = preg_replace("/ and /u", "|", $authorstring);
	$authorstring = preg_replace("/\.,/Uu", "|", $authorstring);
						
	$authorstring = preg_replace("/\|\s*\|/Uu", "|", $authorstring);				
	$authorstring = preg_replace("/\|\s*/Uu", "|", $authorstring);				
	$authors = explode("|", $authorstring);
	
	//echo $authorstring . "\n";
	
	for ($i = 0; $i < count($authors); $i++)
	{
		$authors[$i] = preg_replace('/\.([A-Z])/u', ". $1", $authors[$i]);
		$authors[$i] = preg_replace('/^\s+/u', "", $authors[$i]);
		$authors[$i] = mb_convert_case($authors[$i], MB_CASE_TITLE, 'UTF-8');
		
		$authors[$i] =str_replace('.', '', $authors[$i]);
	}
	
	// try and catch obvious errors
	$j = 0;
	$a = array();
	for ($i = 0; $i < count($authors); $i++)
	{
		if ($split_on_commas)
		{
			if (preg_match('/^(?<lastname>\\p{L}+(-\\p{L}+)?)\s+(?<firstname>[A-Z]\.(\s*[A-Z]\.)?)$/u', $authors[$i], $m))
			{
				$a[$j] = $m['lastname'] . ', ' . $m['firstname'];
				$j++;
			}	
			else
			{
				$a[$j] = $authors[$i];
				$j++;
			}	
		}
		else
		{	
			if (preg_match('/^([A-Z]\.?((\s+[A-Z]\.?)+)?)$/', $authors[$i]))
			{
				$a[$j-1] = $authors[$i] . ' ' . $a[$j-1];
			}
			else
			{
				$a[$j] = $authors[$i];
				$j++;
			}
		}
	}
	$authors = $a;
	
	//print_r($a);
	

	return $authors;
}

//----------------------------------------------------------------------------------------
function reference_from_matches($matches, &$reference = null)
{
	if ($reference == null)
	{
		$reference = new stdclass;
	}
	
	//print_r($matches);

	// title
	$title = '';
	
	if (isset($matches['title']))
	{
		if ($matches['title'] != '')
		{
			$title = $matches['title'];
			$title = html_entity_decode($title, ENT_NOQUOTES, 'UTF-8');
			$title = trim(strip_tags($title));
			$title = preg_replace('/\.$/', '', $title);
			$title = preg_replace('/^\.\s+$/', '', $title);
		}
	}
		
	// authors
	if (isset($matches['authorstring']))
	{
		$authorstring = $matches['authorstring'];
		$reference->authors = authors_from_string($authorstring, false);
	
	
		for ($i = 0; $i < count($reference->authors); $i++)
		{
			$reference->authors[$i]	= preg_replace('/\.$/u', '', $reference->authors[$i]);
			$reference->authors[$i]	= preg_replace('/\.\s+/u', ' ', $reference->authors[$i]);
			$reference->authors[$i]	= preg_replace('/\./u', ' ', $reference->authors[$i]);
			if (strpos($reference->authors[$i], ",") === false)
			{
				$reference->authors[$i]	 = preg_replace('/([A-Z])\.([A-Z])/', '$1 $2', $reference->authors[$i]);
			}
			else
			{
				$author_parts = explode(",", $reference->authors[$i]);
				$forename = $author_parts[1];
				$forename = preg_replace('/([A-Z])\.([A-Z])/u', '$1. $2', trim($forename));
				
				$reference->authors[$i] = $forename . ' ' . $author_parts[0];
			}
		}
	}
	
	// editors
	if (isset($matches['editorstring']))
	{
		$editorstring = $matches['editorstring'];
		$reference->editors = authors_from_string($editorstring);
	
	
		for ($i = 0; $i < count($reference->editors); $i++)
		{
			$reference->editors[$i]	= preg_replace('/\.$/u', '', $reference->editors[$i]);
			$reference->editors[$i]	= preg_replace('/\.\s+/u', ' ', $reference->editors[$i]);
			$reference->editors[$i]	= preg_replace('/\./u', ' ', $reference->editors[$i]);
			if (strpos($reference->editors[$i], ",") === false)
			{
				$reference->editors[$i]	 = preg_replace('/([A-Z])\.([A-Z])/', '$1 $2', $reference->editors[$i]);
			}
			else
			{
				$author_parts = explode(",", $reference->editors[$i]);
				$forename = $author_parts[1];
				$forename = preg_replace('/([A-Z])\.([A-Z])/u', '$1. $2', trim($forename));
				
				$reference->editors[$i] = $forename . ' ' . $author_parts[0];
			}
		}
	}

	
	$reference->genre = 'generic';
	if (isset($matches['journal']))
	{
		$reference->genre = 'article';
	}
	if (isset($matches['publisher']))
	{
		$reference->genre = 'book';
	}
	if (isset($matches['editors']))
	{
		$reference->genre = 'chapter';
	}
	
	if ($title != '')
	{
		$reference->title = $title;
	}
	
	if (isset($matches['journal']))
	{
		$reference->journal = trim(strip_tags($matches['journal']));
	}
	
	if ($matches['series'] != '')
	{
		$reference->series = $matches['series'];
	}
	
	if (isset($matches['volume']))
	{
		$reference->volume = $matches['volume'];
	}
	
	if ($matches['issue'] != '')
	{
		$reference->issue = $matches['issue'];
	}

	if ($matches['issn'] != '')
	{
		$reference->issn = $matches['issn'];
	}

	if (isset($matches['spage']))
	{
		$reference->spage = $matches['spage'];
	}
	if (isset($matches['epage']))
	{
		$reference->epage = $matches['epage'];
	}

	if (isset($matches['pages']))
	{
		if (preg_match('/^(?<spage>.*)\-(?<epage>.*)$/', $matches['pages'], $m))
		{
			$reference->spage = $m['spage'];
			$reference->epage = $m['epage'];
		}
		/*
		else
		{
			$reference->spage = 1;
			$reference->epage = $matches['pages'];
		}	
		*/	
	}
	
	
	if (isset($matches['year']))
	{
		$reference->year = $matches['year'];
	}
	
	if (isset($matches['publisher']))
	{
		$reference->publisher = $matches['publisher'];
	}
	if (isset($matches['publoc']))
	{
		$reference->publoc = $matches['publoc'];
	}
	
	if (isset($matches['book']))
	{
		$reference->book = $matches['book'];
	}
	
	
	
	if (isset($matches['url']))
	{
		$reference->url = $matches['url'];
	}
	if (isset($matches['pdf']))
	{
		$reference->pdf = $matches['pdf'];
	}
	
	if (isset($matches['id']))
	{
		$reference->id = $matches['id'];
	}
	if (isset($matches['date']))
	{
		$reference->date = $matches['date'];
	}
	
	
	return $reference;
}


//----------------------------------------------------------------------------------------
function reference_to_ris($reference)
{
	$field_to_ris_key = array(
		'title' 	=> 'TI',
		'journal' 	=> 'JO',
		'secondary_title' 	=> 'JO',
		'book' 		=> 'T2',
		'issn' 		=> 'SN',
		'volume' 	=> 'VL',
		'issue' 	=> 'IS',
		'spage' 	=> 'SP',
		'epage' 	=> 'EP',
		'year' 		=> 'Y1',
		'date'		=> 'PY',
		'abstract'	=> 'N2',
		'url'		=> 'UR',
		'pdf'		=> 'L1',
		'doi'		=> 'DO',
		'notes'		=> 'N1',
		'oai'		=> 'ID',

		'publisher'	=> 'PB',
		'publoc'	=> 'PP',
		
		'publisher_id' => 'ID'
		
		// correspondence
		
		);
		
	$ris = '';
	
	switch ($reference->genre)
	{
		case 'article':
			$ris .= "TY  - JOUR\n";
			break;

		case 'chapter':
			$ris .= "TY  - CHAP\n";
			break;

		case 'book':
			$ris .= "TY  - BOOK\n";
			break;

		default:
			$ris .= "TY  - GEN\n";
			break;
	}

	//$ris .= "ID  - " . $result->fields['guid'] . "\n";
	
	// Need journal to be output early as some pasring routines that egnerate BibJson
	// assume journal alreday defined by the time we read pages, etc.
	if (isset($reference->journal))
	{
		$ris .= 'JO  - ' . $reference->journal . "\n";
	}

	foreach ($reference as $k => $v)
	{
		switch ($k)
		{
			// eat this
			case 'journal':
				break;
				
			case 'authors':
				foreach ($v as $a)
				{
					if ($a != '')
					{
						$a = str_replace('*', '', $a);
						$a = trim(preg_replace('/\s\s+/u', ' ', $a));						
						$ris .= "AU  - " . $a ."\n";
					}
				}
				break;
				
			case 'editors':
				foreach ($v as $a)
				{
					if ($a != '')
					{
						$ris .= "ED  - " . $a ."\n";
					}
				}
				break;				
				
			case 'date':
				//echo "|$v|\n";
				if (preg_match("/^(?<year>[0-9]{4})\-(?<month>[0-9]{2})\-(?<day>[0-9]{2})$/", $v, $matches))
				{
					//print_r($matches);
					$ris .= "PY  - " . $matches['year'] . "/" . $matches['month'] . "/" . $matches['day']  . "/" . "\n";
					$ris .= "Y1  - " . $matches['year'] . "\n";
				}
				else
				{
					$ris .= "Y1  - " . $v . "\n";
				}		
				break;
				
			default:
				if ($v != '')
				{
					if (isset($field_to_ris_key[$k]))
					{
						$ris .= $field_to_ris_key[$k] . "  - " . $v . "\n";
					}
				}
				break;
		}
	}
	
	$ris .= "ER  - \n";
	$ris .= "\n";
	
	return $ris;
}

//----------------------------------------------------------------------------------------
function reference_to_tsv($reference, $k = null)
{
	$keys = array();
	
	if (isset($k))
	{
		$keys = $k;
	}
	else
	{
		$keys = array(
			'id',
			'authors',
			'title',
			'journal',
			'issn',
			'series',
			'volume',
			'issue',
			'spage',
			'epage',
			'year' ,
			'date',
			'publisher',
			'publoc',
			'doi',
			'url',
			'pdf',
			'notes'
		);
	}
		
	$row = array();
	foreach ($keys as $k)
	{
		switch ($k)
		{
			case 'authors':
				if (isset($reference->{$k}))
				{
					//$row[] = join("&au=", $reference->{$k});
					$row[] = join(";", $reference->{$k});
				}
				else
				{
					$row[] = '';
				}				
				break;
				
			case 'journal':
				if (isset($reference->{$k}))
				{
					$row[] = $reference->{$k};
				}
				else
				{
					if (isset($reference->secondary_title))
					{
						$row[] = $reference->secondary_title;
					}
					else
					{
						$row[] = '';
					}
				}			
				break;
				
			
			default:
				if (isset($reference->{$k}))
				{
					$row[] = $reference->{$k};
				}
				else
				{
					$row[] = '';
				}
			break;
		}
	}
	
	return $row;
}

//----------------------------------------------------------------------------------------
function reference2openurl($reference)
{
	$openurl = '';
	$openurl .= 'ctx_ver=Z39.88-2004&rft_val_fmt=info:ofi/fmt:kev:mtx:journal';
	//$openurl .= '&genre=article';
	
	if (isset($reference->authors))
	{
		foreach ($reference->authors as $author)
		{
			$openurl .= '&rft.au=' . urlencode($author);
		}	
	}
	
	switch ($reference->genre)
	{
		case 'article':
		case 'letter':
			$openurl .= '&rft.atitle=' . urlencode($reference->title);
			
			if (isset($reference->journal))
			{
				$openurl .= '&rft.jtitle=' . urlencode($reference->journal);
			}
			if (isset($reference->secondary_title))
			{
				$openurl .= '&rft.jtitle=' . urlencode($reference->secondary_title);
			}
			break;
			
		default:
			$openurl .= '&rft.title=' . urlencode($reference->title);
			break;
	}
		
	if (isset($reference->issn))
	{
		$openurl .= '&rft.issn=' . $reference->issn;
	}
	if (isset($reference->series))
	{
		$openurl .= '&rft.series=' . $reference->series;
	}
	$openurl .= '&rft.volume=' . $reference->volume;
	
	if (isset($reference->issue))
	{
		$openurl .= '&amp;rft.issue=' . $reference->issue;
	}		
	
	if (isset($reference->spage))
	{
		$openurl .= '&rft.spage=' . $reference->spage;
	}
	if (isset($reference->epage))
	{
		$openurl .= '&rft.epage=' . $reference->epage;
	}
	if (isset($reference->pagination))
	{
		$openurl .= '&rft.pages=' . $reference->pagination;
	}
	
	if (isset($reference->date))
	{
		$openurl .= '&rft.date=' . $reference->date;
	}
	else
	{
		$openurl .= '&rft.date=' . $reference->year;	
	}
	if (isset($reference->lsid))
	{
		$openurl .= '&rft_id=' . $reference->lsid;
	}
	
	if (isset($reference->doi))
	{
		$openurl .= '&rft_id=info:doi/' . $reference->doi;
	}
	
	
	if (isset($reference->url))
	{
		if (preg_match('/http:\/\/hdl.handle.net\//', $reference->url))
		{
			$openurl .= '&rft_id=' . $reference->url;
		}
		if (preg_match('/http:\/\/www.jstor.org\/stable/', $reference->url))
		{
			$openurl .= '&rft_id=' . $reference->url;
		}

		if (preg_match('/biodiversitylibrary.org/', $reference->url))
		{
			$openurl .= '&rft_id=' . $reference->url;
		}
		
	}	
	
	// hack
	if (isset($reference->contributor))
	{
		$openurl .= '&contributor=' . urlencode($reference->contributor);
	}
	

	return $openurl;
}


//----------------------------------------------------------------------------------------
// Create a guid from metadata
function reference_guid_generator ($reference)
{
	if (isset($reference->_id))
	{
		return;
	}
	
	if (isset($reference->doi))
	{
		$reference->_id = $reference->doi;
		return;
	}
	
	if (isset($reference->url))
	{
		$reference->_id = $reference->url;
		return;
	}
	
	if (isset($reference->pdf))
	{
		$reference->_id = $reference->pdf;
		return;
	}
	
	if (isset($reference->pii))
	{
		$reference->_id = $reference->pii;
		return;
	}

	
	if (isset($reference->volume)
		&& isset($reference->issue)
		&& isset($reference->spage)
		&& isset($reference->year)
		&& isset($reference->issn)
		)
	{
		$reference->_id = 'S' . $reference->issn . $reference->year . str_pad($reference->volume, 4, '0', STR_PAD_LEFT) . str_pad($reference->spage, 5, '0', STR_PAD_LEFT);
	}
	else
	{
		$values = array();
		foreach ($reference as $key => $value)
		{
			if (!is_array($value))
			{
				$values[] = $value;
			}
		}
		$reference->_id = md5(join("", $values));
	}
}


//----------------------------------------------------------------------------------------
function reference_to_sql ($reference)
{
	$default_journal = '';
	$default_title = '';
	$default_authors = '';

	$keys = array();
	$values = array();

	$multilingual_keys = array();
	$multilingual_values = array();

	foreach ($reference as $key => $value)
	{
		switch ($key)
		{
			case '_id':
				break;
		
			case 'type':
				break;
				
			case 'journal':
			case 'title':
				if (is_array($value))
				{
					foreach ($value as $language => $v)
					{ 
						$kk = array();
						$vv = array();
						$kk[] = "`key`";
						$vv[] = '"' . $key . '"';

						$kk[] = 'language';
						$vv[] = '"' . $language . '"';
					
						$kk[] = 'value';
						$vv[] = '"' . addcslashes($v, '"') . '"';

						$multilingual_keys[] = $kk;
						$multilingual_values[] = $vv;
						
						if (($key == 'journal') && ($default_journal == ''))
						{
							$keys[] = $key;
							$values[] = '"' . addcslashes($v, '"') . '"';	
							$default_journal =  $v;
						}
						if (($key == 'title') && ($default_title == ''))
						{
							$keys[] = $key;
							$values[] = '"' . addcslashes($v, '"') . '"';	
							$default_title =  $v;
						}						
					}							
				}
				else
				{
					$keys[] = $key;
					$values[] = '"' . addcslashes($value, '"') . '"';	
				}
				break;
				
				
			case 'doi':
				$keys[] = $key;
				$values[] = '"' . addcslashes($value, '"') . '"';
				break;
				
			case 'url':
				$keys[] = $key;
				$values[] = '"' . addcslashes($value, '"') . '"';
				break;
				
			case 'authors':
				// May be an array of arrays (multilingual) or an array of strings
			
				// http://stackoverflow.com/a/1028677
				$k = key($value);
			
				if (is_array($value[$k]))
				{
					// Multilingual
					foreach ($value as $language => $v)
					{ 
						$kk = array();
						$vv = array();
						$kk[] = "`key`";
						$vv[] = '"' . $key . '"';

						$kk[] = 'language';
						$vv[] = '"' . $language . '"';
					
						$kk[] = 'value';
						$vv[] = '"' . addcslashes(join(";", $v), '"') . '"';

						$multilingual_keys[] = $kk;
						$multilingual_values[] = $vv;
						
						if ($default_authors == '')
						{
							$keys[] = $key;
							$values[] = '"' . addcslashes(join(";", $v), '"') . '"';	
							$default_authors =  $v;
						}
						
						
					}												
				}
				else
				{
					// Simple list
					$keys[] = $key;
					$values[] = '"' . addcslashes(join(";", $value), '"') . '"';
				}
				break;
					
			default:
				$keys[] = $key;
				$values[] = '"' . addcslashes($value, '"') . '"';			
				break;
		}
	}
	
	
	// Generate a guid if one not found
	reference_guid_generator($reference);
	
	$keys[] = 'guid';
	$values[] = '"' . $reference->_id . '"';
	
	if (0)
	{
		print_r($keys);
		print_r($values);

		print_r($multilingual_keys);
		print_r($multilingual_values);
	}
		
	// populate from scratch
	$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
		. join(',', $values) . ');';
	echo $sql . "\n";

	$n = count($multilingual_keys);
	for($i =0; $i < $n; $i++)
	{
		$multilingual_keys[$i][] = 'guid';
		$multilingual_values[$i][] = '"' . $reference->_id . '"';

		$sql = 'REPLACE INTO multilingual(' . join(',', $multilingual_keys[$i]) . ') values('
			. join(',', $multilingual_values[$i]) . ');';
		echo $sql . "\n";
	}		
	
	
	
}
		
//----------------------------------------------------------------------------------------
function reference_to_bibjson($reference)
{
	
	reference_guid_generator($reference);
	
	$obj = new stdclass;
	$obj->_id = $reference->_id;		
	
	// By default each reference is its own cluster
	$obj->cluster_id = $obj->_id;
	
	if (isset($reference->citation))
	{
		$obj->citation_string = $reference->citation;
	}
	
	$obj->author = array();

	if (isset($reference->authors))
	{
		foreach ($reference->authors as $a)
		{
	
			$parts = parse_name($a);
		
			$author = new stdClass();
		
			if (isset($parts['last']))
			{
				$author->lastname = $parts['last'];
			}
			if (isset($parts['suffix']))
			{
				$author->suffix = $parts['suffix'];
			}
			if (isset($parts['first']))
			{
				$author->firstname = $parts['first'];
			
				if (array_key_exists('middle', $parts))
				{
					$author->firstname .= ' ' . $parts['middle'];
				}
			}
			$author->firstname = preg_replace('/\./Uu', '', $author->firstname);
			$author->name = $author->firstname . ' ' . $author->lastname;

			$obj->author[] = $author;
		}
	}	
	switch ($reference->genre)
	{
		case 'article':
		case 'book':
		case 'chapter':
			$obj->type = $reference->genre;
			break;

		default:
			$obj->type = 'generic';
			break;
	}			
	
	if (isset($reference->title) && $reference->title != '')
	{
		$obj->title = $reference->title;
	}
	
	if ($reference->genre == 'book')
	{
		if (isset($reference->publisher))
		{
			$obj->publisher = new stdclass;
			$obj->publisher->name = $reference->publisher;
			
			if (isset($reference->publoc))
			{
				$obj->publisher->address = $reference->publoc;
			}
		}

		if (isset($reference->oclc))
		{
			if ($reference->oclc != 0)
			{
				$identifier = new stdclass;
				$identifier->type = 'oclc';
				$identifier->id = (Integer)$reference->oclc; 
				$obj->book->identifier[] = $identifier;
			}
		}
		
		if (isset($reference->secondary_authors))
		{		
			$obj->book->editor = array();
			foreach ($reference->secondary_authors as $a)
			{
				$author = new stdclass;
				
				if (($a->forename == '') || ($a->lastname == ''))
				{
				}
				else
				{		
					$author->firstname = $a->forename;
					$author->lastname = $a->lastname;
				}
				$author->name = trim($a->forename . ' ' . $a->lastname);
				
				$obj->book->editor[] = $author;
			}
		}
		
		if (isset($reference->spage))
		{
			$obj->pages = $reference->spage;
		}
		if (isset($reference->epage))
		{
			$obj->pages .= '--' . $reference->epage;
		}		
				
	}
	
	
	if ($reference->genre == 'chapter')
	{
		$obj->book = new stdclass;
		$obj->book->title = $reference->journal;
		
		if (isset($reference->secondary_authors))
		{		
			$obj->book->editor = array();
			foreach ($reference->secondary_authors as $a)
			{
				$author = new stdclass;
				
				if (($a->forename == '') || ($a->lastname == ''))
				{
				}
				else
				{		
					$author->firstname = $a->forename;
					$author->lastname = $a->lastname;
				}
				$author->name = trim($a->forename . ' ' . $a->lastname);
				
				$obj->book->editor[] = $author;
			}
		}
		
		if (isset($reference->publisher))
		{
			$obj->book->publisher = new stdclass;
			$obj->book->publisher->name = $reference->publisher;
			
			if (isset($reference->publoc))
			{
				$obj->book->publisher->address = $reference->publoc;
			}
		}
		
		if (isset($reference->oclc))
		{
			$identifier = new stdclass;
			$identifier->type = 'oclc';
			$identifier->id = (Integer)$reference->oclc; 
			$obj->book->identifier[] = $identifier;
		}
		
		
		if (isset($reference->spage))
		{
			$obj->pages = $reference->spage;
		}
		if (isset($reference->epage))
		{
			$obj->pages .= '--' . $reference->epage;
		}		
	}
	

	if ($reference->genre == 'article')
	{
		$obj->journal = new stdclass;
		$obj->journal->name = $reference->journal;
		
		if (isset($reference->series))
		{
			$obj->journal->series = $reference->series;
		}
		
		$obj->journal->volume = $reference->volume;
		
		if (isset($reference->issue))
		{
			$obj->journal->issue = $reference->issue;
		}
	
		if (isset($reference->spage))
		{
			$obj->journal->pages = $reference->spage;
		}
		if (isset($reference->epage))
		{
			$obj->journal->pages .= '--' . $reference->epage;
		}
		if (isset($reference->issn))
		{
			$identifier = new stdclass;
			$identifier->type = 'issn';
			$identifier->id = $reference->issn; 
			$obj->journal->identifier[] = $identifier;
		}
		if (isset($reference->oclc))
		{
			$identifier = new stdclass;
			$identifier->type = 'oclc';
			$identifier->id = (Integer)$reference->oclc; 
			$obj->journal->identifier[] = $identifier;
		}
	}
	
	if (isset($reference->year))
	{
		$obj->year = $reference->year;
	}
	
	// Links
	$obj->link = array();
	if (isset($reference->url))
	{
		$link = new stdclass;
		$link->anchor = 'LINK';
		$link->url = $reference->url;
		$obj->link[] = $link;
	}
	if (isset($reference->pdf))
	{
		$link = new stdclass;
		$link->anchor = 'PDF';
		$link->url = $reference->pdf;
		$obj->link[] = $link;
	}
	
	// Identifiers
	$obj->identifier = array();
	
	if (isset($reference->doi))
	{
		$identifier = new stdclass;
		$identifier->type = 'doi';
		$identifier->id = $reference->doi; 
		$obj->identifier[] = $identifier;
		
		// Use DOI to define cluster
		$obj->cluster_id = $reference->doi;
	}
	if (isset($reference->hdl))
	{
		$identifier = new stdclass;
		$identifier->type = 'handle';
		$identifier->id = $reference->handle; 
		$obj->identifier[] = $identifier;
	}
	if (isset($reference->isbn))
	{
		$identifier = new stdclass;
		$identifier->type = 'isbn';
		$identifier->id = $reference->isbn; 
		$obj->identifier[] = $identifier;
	}
	if (isset($reference->isbn13))
	{
		$identifier = new stdclass;
		$identifier->type = 'isbn13';
		$identifier->id = $reference->isbn13; 
		$obj->identifier[] = $identifier;
	}

	if (isset($reference->lsid))
	{
		if (preg_match('/urn:lsid:zoobank.org:pub:(?<id>.*)/', $reference->lsid, $m))
		{
			$identifier = new stdclass;
			$identifier->type = 'lsid';
			$identifier->id = $reference->lsid; 
			$obj->identifier[] = $identifier;			
		}
	}

	if (isset($reference->pmid))
	{
		$identifier = new stdclass;
		$identifier->type = 'pmid';
		$identifier->id = (Integer)$reference->pmid; 
		$obj->identifier[] = $identifier;			
	}
	
	if (isset($reference->pmid))
	{
		$identifier = new stdclass;
		$identifier->type = 'pmc';
		$identifier->id = $reference->pmc; 
		$obj->identifier[] = $identifier;			
	}
	
	if (isset($reference->jstor))
	{
		$identifier = new stdclass;
		$identifier->type = 'jstor';
		$identifier->id = (Integer)$reference->jstor; 
		$obj->identifier[] = $identifier;			
	}
	
	// openurl
	$openurl = reference2openurl($reference);
	if ($openurl != '')
	{
		$link = new stdclass;
		$link->anchor = 'OpenURL';
		$link->url = $openurl;
		$obj->link[] = $link;
	}	
	
	
	
	// cleanup
	
	if (count($obj->author) == 0)
	{
		unset($obj->author);
	}
	if (count($obj->identifier) == 0)
	{
		unset($obj->identifier);
	}
	if (count($obj->link) == 0)
	{
		unset($obj->link);
	}
	
	
	
	// provenance
	if (isset($reference->source))
	{
		$obj->source = $reference->source;
	}
	

	compute_hashes($obj);
	
	return $obj;
}

//----------------------------------------------------------------------------------------
function compute_hashes(&$obj)
{
	// hashes to help clustering
	if (isset($obj->author[0]) && isset($obj->year))
	{
		$name = '';
		if (isset($obj->author[0]->lastname))
		{
			$name = $obj->author[0]->lastname;
		}
		else
		{
			$name = $obj->author[0]->name;
		}
	
		$obj->hash_author = str_replace(' ', '', finger_print($name)) . $obj->year;
	}
	
	if (isset($obj->year) 
		&& isset($obj->journal) 
		&& isset($obj->journal->volume) 
		&& isset($obj->journal->pages) 
		)
	{
		$obj->hash_numbers = array();
		$obj->hash_numbers[] = $obj->year;
		$obj->hash_numbers[] = $obj->journal->volume;
		
		$page = $obj->journal->pages;
		$parts = explode('--', $page);
		$obj->hash_numbers[] = $parts[0];
	}
}

//----------------------------------------------------------------------------------------
// Dump to Wikipsecies to help editing
function reference_to_wikispecies($reference)
{
	$string = '';
	
	$string .= '* ';
	
	$openurl = '';
	$openurl .= 'ctx_ver=Z39.88-2004&rft_val_fmt=info:ofi/fmt:kev:mtx:journal';
	//$openurl .= '&genre=article';
	
	if (isset($reference->authors))
	{
		$authors = array();
		foreach ($reference->authors as $author)
		{
			$authors[] = '{{aut|' . $author . '}}';
		}	
		
		$string .= join(', ', $authors);
	}
	
	if (isset($reference->year))
	{
		$string .= ' ' . $reference->year . '. ';
	}
	
	switch ($reference->genre)
	{
		case 'article':
		case 'letter':		
			$string .= ' ' . $reference->title;
		
			if (isset($reference->journal))
			{
				$string .= " ''" . $reference->journal . "''";
			}
			if (isset($reference->secondary_title))
			{
				$string .= " ''" . $reference->secondary_title;
			}
			break;
			
		default:
			$string .= ' ' . $reference->title;
			break;
	}
		
	if (isset($reference->issn))
	{
		$openurl .= '&rft.issn=' . $reference->issn;
	}
	
	
	if (isset($reference->series))
	{
		$string .= ' ' . $reference->series;
	}
	
	if (isset($reference->volume))
	{
		$string .= ' ' . $reference->volume;
	}
	
	if (isset($reference->issue))
	{
		$string .= '(' . $reference->issue . ')';
	}		
	
	if (isset($reference->spage))
	{
		$string .= ': ' . $reference->spage;
	}
	if (isset($reference->epage))
	{
		$string .= '-' . $reference->epage;
	}
	if (isset($reference->pagination))
	{
		$string .= ' ' . $reference->pagination;
	}
	
	
	/*
	if (isset($reference->lsid))
	{
		$openurl .= '&rft_id=' . $reference->lsid;
	}
	
	if (isset($reference->doi))
	{
		$openurl .= '&rft_id=info:doi/' . $reference->doi;
	}
	
	
	if (isset($reference->url))
	{
		if (preg_match('/http:\/\/hdl.handle.net\//', $reference->url))
		{
			$openurl .= '&rft_id=' . $reference->url;
		}
		if (preg_match('/http:\/\/www.jstor.org\/stable/', $reference->url))
		{
			$openurl .= '&rft_id=' . $reference->url;
		}

		if (preg_match('/biodiversitylibrary.org/', $reference->url))
		{
			$openurl .= '&rft_id=' . $reference->url;
		}
		
	}	
	*/	

	return $string;
}


?>