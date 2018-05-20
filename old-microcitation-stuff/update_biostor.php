<?php

// Use microcitation data to update BioStor records

/*

SELECT * INTO OUTFILE '/tmp/refs.txt'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\n'
FROM rdmp_reference
WHERE issn='0323-6145' AND doi IS NULL;


*/

require_once(dirname(__FILE__) . '/config.inc.php');
require_once(dirname(__FILE__) . '/adodb5/adodb.inc.php');

//--------------------------------------------------------------------------------------------------
$db = NewADOConnection('mysql');
$db->Connect("localhost", 
	$config['db_user'] , $config['db_passwd'] , $config['db_name']);

// Ensure fields are (only) indexed by column name
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$db->EXECUTE("set names 'utf8'"); 

$issn = '0374-5481';
$issn = '0323-6145';

$sql = 'select rdmp_reference.reference_id,
publications.title, publications.journal, publications.series, publications.volume, publications.issue, publications.spage, publications.epage,
publications.doi,
publications.authors
 from rdmp_reference 
inner join publications using(issn)
where publications.issn = "' . $issn . '"
and rdmp_reference.volume= publications.volume
and rdmp_reference.spage= publications.spage;';

// and rdmp_reference.series = publications.series
// and rdmp_reference.epage= publications.epage

echo $sql;

$ids = array();

$result = $db->Execute($sql);
if ($result == false) die("failed [" . __LINE__ . "]: " . $sql);

while (!$result->EOF) 
{

	$parameters = array();
	$parameters['update'] = '';

	foreach ($result->fields as $k => $v)
	{
		if ($v != '')
		{
			switch ($k)
			{
				case 'reference_id':
					$ids[] = $v;
				case 'title':
				case 'series':
				case 'volume':
				case 'issue':
				case 'spage':
				case 'epage':
				case 'doi':
					$parameters[$k] = $v;
					break;

				case 'journal':
					$parameters['secondary_title'] = $v;
					break;
		
				case 'authors':
					$authors = preg_split("/;/u", $v);
					$parameters[$k] = join("\n", $authors);
					break;
					
				default:
					break;
			}
		}
	}
	
	print_r($parameters);
	
	$url = 'http://direct.biostor.org/update.php';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   
	curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);

	$response = curl_exec($ch);
	if($response == FALSE) 
	{
		$errorText = curl_error($ch);
		curl_close($ch);
		die($errorText);
	}
	
	$info = curl_getinfo($ch);
	$http_code = $info['http_code'];
	
	if ($http_code != 200)
	{
		echo $response;	
		die ("Triple store returned $http_code\n");
	}
	
	
	curl_close($ch);

	echo $response;
	
	
	
	
	$result->MoveNext();
}
	
print_r($ids);

echo join(",", $ids) . "\n";


?>