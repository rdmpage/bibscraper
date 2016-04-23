<?php

//----------------------------------------------------------------------------------------
function pause()
{
	$rand = rand(1000000, 3000000);
	usleep($rand);
}


//----------------------------------------------------------------------------------------
function reference_to_ris($reference)
{
	$field_to_ris_key = array(
		'title' 	=> 'TI',
		'journal' 	=> 'JO',
		'issn' 		=> 'SN',
		'volume' 	=> 'VL',
		'issue' 	=> 'IS',
		'spage' 	=> 'SP',
		'epage' 	=> 'EP',
		'year' 		=> 'Y1',
		'abstract'	=> 'N2',
		'url'		=> 'UR',
		'pdf'		=> 'L1',
		'doi'		=> 'DO'
		);
		
	$ris = '';
	
	$ris .= "TY  - JOUR\n";
	//$ris .= "ID  - " . $result->fields['guid'] . "\n";

	foreach ($reference as $k => $v)
	{
		switch ($k)
		{
			case 'authors':
				foreach ($v as $a)
				{
					$ris .= "AU  - " . $a ."\n";
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
function reference_to_tsv($reference)
{
	$keys = array(
		'_id',
		'title',
		'authors',
		'journal',
		'issn',
		'volume',
		'issue',
		'spage',
		'epage',
		'year',
		'doi',
		'url',
		'pdf'
		);
		
	$row = array();
	foreach ($keys as $k)
	{
		switch ($k)
		{
			case 'authors':
				if (isset($reference->{$k}))
				{
					$row[] = join(";", $reference->{$k});
				}
				else
				{
					$row[] = '';
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
		

?>