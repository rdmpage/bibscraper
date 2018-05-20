<?php

require_once('lib.php');

$urls = array(
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000003/art00012',
'http://www.ingentaconnect.com/content/iapt/tax/2008/00000057/00000002/art00047',
'http://www.ingentaconnect.com/content/iapt/tax/2009/00000058/00000001/art00044',
'http://www.ingentaconnect.com/content/iapt/tax/2004/00000053/00000001/art00005',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000002/art00003',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000002/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000002/art00019',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000002/art00020',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000002/art00021',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000002/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000003/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000003/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000003/art00018',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000003/art00019',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000003/art00011',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000003/art00025',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000004/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000004/art00014',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000004/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000004/art00021',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000005/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000005/art00011',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000005/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000005/art00017',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000006/art00003',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000006/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000006/art00007',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000006/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000006/art00011',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000006/art00012',
'http://www.ingentaconnect.com/content/iapt/tax/2010/00000059/00000006/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00006',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00008',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00011',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00012',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00014',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000001/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000002/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000002/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000002/art00020',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000003/art00002',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000003/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000003/art00005',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000003/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000003/art00014',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000003/art00039',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00011',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00012',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00014',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00016',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00017',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000004/art00022',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00003',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00012',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00014',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00016',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00029',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000005/art00038',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000006/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000006/art00012',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000006/art00014',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000006/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000006/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2011/00000060/00000006/art00018',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000001/art00003',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000001/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000001/art00005',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000001/art00006',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000001/art00007',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000001/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000001/art00012',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000002/art00002',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000002/art00003',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000002/art00011',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000002/art00013',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000003/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000003/art00008',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000003/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000003/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000003/art00015',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000004/art00002',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000004/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000004/art00005',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000005/art00002',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000005/art00003',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000005/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000005/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000006/art00003',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000006/art00004',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000006/art00005',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000006/art00006',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000006/art00008',
'http://www.ingentaconnect.com/content/iapt/tax/2012/00000061/00000006/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2013/00000062/00000001/art00008',
'http://www.ingentaconnect.com/content/iapt/tax/2013/00000062/00000001/art00009',
'http://www.ingentaconnect.com/content/iapt/tax/2013/00000062/00000001/art00010',
'http://www.ingentaconnect.com/content/iapt/tax/2013/00000062/00000001/art00011',
'http://www.ingentaconnect.com/content/iapt/tax/2013/00000062/00000001/art00006',
'http://www.ingentaconnect.com/content/iapt/tax/2013/00000062/00000001/art00013'

);

foreach ($urls as $url)
{
	$ris = get($url . '?format=ris');
	
	//echo $ris;
	
	// fix
	
	$rows = explode("\n", $ris);
	
	//print_r($rows);
	$keys = array();
	
	$last_key = '';
	
	foreach ($rows as $row)
	{
		$parts = preg_split ('/  -\s+/', $row);
		
		//print_r($parts);
		
		if (isset($parts[1]))
		{
			$key = trim($parts[0]);
			$value = trim($parts[1]); // clean up any leading and trailing spaces
			
			$keys[$key][] = strip_tags($value);
			$last_key = $key;
		}		
		else
		{
			if ($last_key == 'N2')
			{
				$keys['N2'][0] .= strip_tags(trim($row));
			}
		}
	}
	
	//print_r($keys);
	
	foreach ($keys as $k => $v)
	{
		foreach ($v as $x)
		{
			echo $k . '  - ' . $x . "\n";
		}
	}
	echo "\n";
								
}

?>
