<?php

// test whether PDF exists online

 //-----------------------------------------------------------------------------------
// Do HTTP HEAD to see if a document exists
function exists($url)
{
	$ch = curl_init(); 

	curl_setopt ($ch, CURLOPT_URL, $url); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 		
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "HEAD");
	
	// http://stackoverflow.com/a/770200
	curl_setopt($ch, CURLOPT_NOBODY, true);

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	return ($http_code == 200);
}


$filename = dirname(__FILE__) . '/pdfs.txt';

$file_handle = fopen($filename, "r");

$failed = array();

$count = 1;

while (!feof($file_handle)) 
{
	$pdf = trim(fgets($file_handle));
	
	if (preg_match('/^#/', $pdf))
	{
		// skip
	}
	else
	{
		echo $pdf . ' ... ';
		if (exists($pdf))
		{
			echo "OK";
		}
		else
		{
			echo "Failed";
			$failed[] = $pdf;
		}
		echo "\n";

		
		if ($count++ % 10 == 0)
		{
			$rand = rand(1000000, 3000000);
			echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
			usleep($rand);
		}
	}	
}

echo "--- list ---\n";
foreach ($failed as $pdf)
{
	echo "$pdf\n";
}

?>