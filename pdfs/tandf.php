<?php

error_reporting(E_ALL ^ E_DEPRECATED);


//----------------------------------------------------------------------------------------
function get($doi)
{

	$url = 'https://www.tandfonline.com/doi/pdf/' . $doi . '?needAccess=true';
	
	echo $url . "\n";

	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_COOKIEJAR => 'cookie.txt'
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	
	print_r($info);
	
	curl_setopt($ch, CURLOPT_URL, $url .= '&cookieSet=1');
	
	
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	
	print_r($info);	
	
	curl_close($ch);
	
	echo "data=$data\n";

	
	$filename = $doi . '.pdf';
	$filename = str_replace('/', '-', $filename);
	
	file_put_contents($filename, $data);
}


//----------------------------------------------------------------------------------------
function extra($doi)
{

	$filename = $doi . '.pdf';
	$filename = str_replace('/', '-', $filename);
	
	$url = 'https://www.tandfonline.com/doi/pdf/' . $doi;

	echo "/Users/rpage/Desktop/newnewpdf/$filename\t$url\n";
}

//----------------------------------------------------------------------------------------
function tosql($doi)
{
	$url = 'https://www.tandfonline.com/doi/pdf/' . $doi;
	
	// ?needAccess=true
	echo 'UPDATE bibliography SET pdf="' . $url . '", free="N" WHERE doi="' . $doi . '";' . "\n";
}

$count = 1;

$dois=array(
'10.1080/03036758.1983.10420804'
);

foreach ($dois as $doi)
{
	if (1)
	{
		get($doi);

		if ($count++ % 10 == 0)
		{
			$rand = rand(1000000, 3000000);
			echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
			usleep($rand);
		}
	}
	
	if (0)
	{
		extra($doi);
	}
	
	if (0)
	{
		tosql($doi);
	}
	
	
}



?>
