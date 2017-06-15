<?php

// Parse a TSV file and extract references



$filename = 'Reptile_database2014-12.tsv';


$keys = array(
'classification', // 0
'genus',
'species',
'author',
'year',
'parentheses',
'synonyms', // 6
'subspecies',
'commonnames', // 8
'distribution', // 9
'comments',
'types', // 11
'links',
'references',
'comments'
);

$count = 0;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$row = trim(fgets($file_handle));
		
	$parts = explode("\t",$row);
	
	//print_r($parts);
	
	$obj = new stdclass;
	
	$data = array();
	
	$d = array();
	
	$i = 0;
	
	
	
	foreach ($parts as $k => $v)
	{
		$data[$k] = $v;
		
		switch ($k)
		{
			case 0:
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 8:
			case 11:
			case 14:
				if ($v != '')
				{
					$obj->{$keys[$k]} = $v;
				}
				break;
				
			default:
				break;
		}
		
		if (preg_match('/\x0B/', $v))
		{
			//echo "$k has parts\n";
			$r = preg_split('/\x0B/', $v);
			//print_r($r);
			
			$x = array();
			foreach ($r as $kk => $vv)
			{
				if ($vv != '')
				{
					$x[] = $vv;
				}
			}
			
			//exit();
			
			$obj->{$keys[$k]} = $x;
			/*
			switch ($k)
			{
			
				case 6:
					$obj->synonyms = $x;
					break;
					
				case 9:
					$obj->distribution = $x;
					break;

				case 9:
					$obj->distribution = $x;
					break;
			
				case 13:
					$obj->references = $x;
					break;
					
				default:
					break;
			}
			*/
			
		}
		
		
		
	
	}
	
	print_r($obj);
	
	
	if ($count++ > 1000) 
	{
		exit();
	}
	
	

	


	
}

//echo '</table>';

?>
