<?php

require_once('../../utils.php');

//--------------------------------------------------------------------------------------------------
// http://stackoverflow.com/a/5996888/9684
function translate_quoted($string) {
  $search  = array("\\t", "\\n", "\\r");
  $replace = array( "\t",  "\n",  "\r");
  return str_replace($search, $replace, $string);
}


$volume_to_year = array(
'1'=> '1874',   
'1'=> '1875',   
'1'=> '1876',   
'2'=> '1877',   
'2'=> '1878',   
'2'=> '1879',   
'3'=> '1880',   
'3'=> '1881',   
'3'=> '1882',   
'4'=> '1883',   
'4'=> '1884',   
'4'=> '1885',   
'5'=> '1888',   
'5'=> '1889',   
'5'=> '1890',   
'6'=> '1891',   
'6'=> '1892',   
'6'=> '1893',   
'7'=> '1894',   
'7'=> '1895',   
'7'=> '1896',   
'8'=> '1897',   
'8'=> '1898',   
'8'=> '1899',   
'9'=> '1900',   
'9'=> '1901',   
'9'=> '1902',   
'10'=> '1903',  
'11'=> '1904',  
'12'=> '1905',  
'13'=> '1906',  
'14'=> '1907',  
'15'=> '1908',  
'16'=> '1909',  
'17'=> '1910',  
'18'=> '1911',  
'19'=> '1912',  
'20'=> '1913',  
'21'=> '1914',  
'22'=> '1915',  
'23'=> '1916',  
'24'=> '1917',  
'25'=> '1918',  
'26'=> '1919',  
'27'=> '1920',  
'28'=> '1921',  
'29'=> '1922',  
'30'=> '1923',  
'31'=> '1924',  
'32'=> '1925',  
'33'=> '1926',  
'34'=> '1927',  
'35'=> '1928',  
'36'=> '1929',  
'37'=> '1930',  
'41'=> '1934',  
'42'=> '1935',  
'43'=> '1936',  
'44'=> '1937',  
'45'=> '1938',  
'46'=> '1939',  
'47'=> '1940',  
'48'=> '1941',  
'49'=> '1942',  
'50'=> '1943',  
'51'=> '1944',  
'52'=> '1945',  
'53'=> '1946',  
'54'=> '1947',  
'55'=> '1948',  
'56'=> '1949',  
'57'=> '1950',  
'58'=> '1951',  
'59'=> '1952',  
'60'=> '1953',  
'61'=> '1954',  
'62'=> '1955',  
'63'=> '1956',  
'64'=> '1957',  
'65'=> '1958',  
'66'=> '1959',  
'67'=> '1960',  
'68'=> '1961',  
'69'=> '1962',  
'70'=> '1963',  
'71'=> '1964',  
'72'=> '1965',  
'73'=> '1966',  
'74'=> '1967',  
'75'=> '1968',  
'76'=> '1969',  
'77'=> '1970',  
'78'=> '1971',  
'79'=> '1972',  
'80'=> '1973',  
'81'=> '1974',  
'82'=> '1975',  
'83'=> '1976',  
'84'=> '1977',  
'85'=> '1978',  
'88'=> '1981',  
'89'=> '1982',  
'90'=> '1983',  
'91'=> '1984',  
'92'=> '1985',  
'93'=> '1986',  
'94'=> '1987',  
'95'=> '1988',  
'2009'=> '2009',
'2010'=> '2010',
'2011'=> '2011',
'2012'=> '2012',
'2013'=> '2013',
'2014'=> '2014',
'2015'=> '2015',
'2016'=> '2016'
);



$filename = 'psyche-metadata-for-biostor.csv';

$keys = array();

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	//$row = trim(fgets($file_handle));
	
		$row = fgetcsv(
			$file_handle, 
			0, 
			translate_quoted(','),
			translate_quoted('"')
			);
	
		
	//$parts = explode(",",$row);
	
	if (count($keys) == 0)
	{
		$keys = $row;
	}
	else
	{
		// print_r($row);
		
		/*
		$parameters = array();
		
		// default for journal
		$parameters['title'] = 'Psyche';
		$parameters['issn'] = '0033-2615';
		
		
		foreach ($keys as $k => $v)
		{
			// volume,issue,first page,last page,title,authors
			switch ($v)
			{
				//case 
				
				case 'first page':
					$parameters['spage'] = $row[$k];
					break;

				case 'last page':
					$parameters['epage'] = $row[$k];
					break;

				case 'title':
					$parameters['atitle'] = $row[$k];
					break;
					
				case 'volume':
					$parameters['volume'] = $row[$k];
					
					$parameters['year'] = $volume_to_year[$row[$k]];
					break;

				case 'authors':
					$parameters['au'] = join('&au=', explode(';', $row[$k]));
					break;
				
				default:
					$parameters[$v] = $row[$k];
					break;
			}
		}
					
		
		print_r($parameters);
		
		$url = 'http://direct.biostor.org/openurl?' . http_build_query($parameters);
		
		echo $url . "\n";
		
		*/
		
		
		// default for journal
		$reference = new stdclass;
		$reference->genre = 'article';
		
		
		foreach ($keys as $k => $v)
		{
			// volume,issue,first page,last page,title,authors
			switch ($v)
			{
				//case 
				
				case 'first page':
					$reference->spage = $row[$k];
					break;

				case 'last page':
					$reference->epage = $row[$k];
					break;

				case 'title':
					$reference->title = $row[$k];
					break;
					
				case 'volume':
					$reference->journal = 'Psyche';
					$reference->issn = '0033-2615';
				
					$reference->volume = $row[$k];
					$reference->year = $volume_to_year[$row[$k]];
					break;

				case 'authors':
					$reference->authors = explode(';', $row[$k]);
					break;
				
				default:
					$reference->{$v} = $row[$k];
					break;
			}
		}	
		
		//print_r($reference);	
		
		echo reference_to_ris($reference);
		
		
			
	}
	

	

	
}



?>

