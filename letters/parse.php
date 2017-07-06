<?php



//--------------------------------------------------------------------------------------------------
// http://stackoverflow.com/a/5996888/9684
function translate_quoted($string) {
  $search  = array("\\t", "\\n", "\\r");
  $replace = array( "\t",  "\n",  "\r");
  return str_replace($search, $replace, $string);
}

//--------------------------------------------------------------------------------------------------
function is_empty_cell($value)
{
	$empty = false;
	
	if ($value == '') $empty = true;
	if ($value == '\N') $empty = true;
	
	return $empty;
}

$filename = 'Asa Gray Correspondence A - BioStor.csv';

$file = @fopen($filename, "r") or die("couldn't open $filename");

$keys = array();
		
$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$row = fgetcsv(
		$file_handle, 
		0, 
		translate_quoted(','),
		translate_quoted('"')
		);
		
	print_r($row);

	$go = is_array($row);
			
	if ($go && ($row_count == 0))
	{
		$keys = $row;
		$go = false;
	}
	if ($go)
	{
		$obj = new stdclass;
		
		$column_count = 0;		
				
		// Create a simpe key-value object with local identifiers 
		foreach ($row as $key => $value)
		{
			if (!is_empty_cell($value))
			{
				$value = trim($value);
				
				$obj->{$keys[$column_count]} = $value;
			}
			$column_count++;
		}
		
		print_r($obj);
		
		
// TY,AU,NI,PB,DA,SP,EP,T1,ID,TI,VL
// PCOMM,"Beal, William J.",to Asa Gray,Harvard University Botany Libraries, 1873-11-13,47926076,47926075,Asa Gray correspondence. Senders Be-Bo,104716,"Beal, William J. to Asa Gray Nov. 13, 1873",185537
		
		// dump to RIS
		
		$spage = 0;
		$epage = 0;
		
		echo "TY  - JOUR\n";
		
		foreach ($obj as $k => $v)
		{
			switch ($k)
			{
				case 'AU':
					echo "AU  - $v\n";
					break;			
			
				case 'T1':
					echo "JO  - $v\n";
					break;

				case 'TI':
					echo "TI  - $v\n";
					break;

				case 'PB':
					echo "PB  - $v\n";
					break;
					
				case 'SP':
					$spage = $v;
					echo "SP  - 1\n";
					echo "N1  - $v\n";
					break;

				case 'EP':
					$epage = $v;
					echo "EP  - " . (abs($epage - $spage) + 1) . "\n";
					break;

				case 'DA':
					echo "PY  - $v\n";
					break;
					
				default:
					break;
			}
		}
		echo "ER  - \n\n";		
		
		
		
	}
	$row_count++;
	
}