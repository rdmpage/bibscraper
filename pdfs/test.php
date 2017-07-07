<?php

require_once (dirname(dirname(__FILE__)) . '/lib.php');

function get_pdf_filename($pdf)
{
	
	if ($filename == '')
	{
		if (preg_match('/file_no=(?<id>\d+([A-Z]\d+)?)\&/', $filename, $m))
		{
			$filename = $m['id'];
		}
	}
	
	
	if ($filename == '')
	{
		if (preg_match('/(download|view)\/(?<id1>\d+)\/(?<id2>\d+)$/', $pdf, $m))
		{
			$filename = $m['id1'] . '-' . $m['id2'];
		}
	}
	
	// http://www.scielo.br/pdf/abb/v15n1/5161.pdf'
	if ($filename == '')
	{
		if (preg_match('/pdf\/(.*)\/(.*)\/(.*).pdf$/', $pdf, $m))
		{
			//print_r($m); exit();
			$filename = $m[1] . '-' . $m[2] . '-' . $m[3] . '.pdf';
		}
	}
	
	// http://www1.montpellier.inra.fr/CBGP/acarologia/export_pdf.php?id=4089&typefile=pdf
	if ($filename == '')
	{
		if (preg_match('/\?id=(\d+.*)&/', $pdf, $m))
		{
			//print_r($m); exit();
			$filename = 'acarologia-' . $m[1] . '.pdf';
		}
	}
		
	// if no name use basename
	if ($filename == '')
	{
		$filename = basename($pdf);
		$filename = str_replace('lognavi?name=nels&lang=en&type=pdf&id=', '', $filename);
	}
	

	if (!preg_match('/\.pdf$/', $filename))
	{
		$filename .= '.pdf';
	}
	//echo "filename=$filename\n";
	
	return $filename;
}


$filename = dirname(__FILE__) . '/pdfs.txt';

$file_handle = fopen($filename, "r");

$pdfs = array();

$sha1s = array();

$sqls = array();

while (!feof($file_handle)) 
{
	$pdf = trim(fgets($file_handle));
	
	if (preg_match('/^#/', $pdf))
	{
		// skip
	}
	else
	{
	
		$url = 'http://bionames.org/bionames-archive/havepdf.php?url=' . urlencode($pdf) . '&noredirect=1&format=json';
		//$url = 'http://bionames.org/bionames-archive/havepdf.php?url=' . $pdf . '&noredirect=1&format=json';

		$json = get($url);
		
		//echo $url . "\n";
		
		//echo $json;
	
		$obj = json_decode($json);
		
	
		if ($obj->http_code == 200)
		{		
			echo  "Have: " . $obj->sha1 . "\n";
			
			$sqls[] = 'REPLACE INTO sha1(pdf, sha1) VALUES("' . $pdf . '","' . $obj->sha1 . '");';
			
			$sha1s[] = $obj->sha1;
		}
		else
		{
			echo "Not found\n";
			$pdfs[]  = $pdf;			
		}
	}	
}

echo "--- list ---\n";
foreach ($pdfs as $pdf)
{
	echo "$pdf\n";
}

echo "--- sha1 ---\n";
file_put_contents(dirname(__FILE__) . '/file_sha.txt', join("\n", $sha1s));

echo "--- curl fetch.sh ---\n";
$curl = "#!/bin/sh\n\n";
foreach ($pdfs as $pdf)
{
	$filename = get_pdf_filename($pdf);

	$curl .= "curl -L '$pdf' > '" . $filename . "'\n";
}
file_put_contents(dirname(__FILE__) . '/fetch.sh', $curl);

echo "--- extra.txt ---\n";
$extra = '';
foreach ($pdfs as $pdf)
{
	$filename = get_pdf_filename($pdf);

	$extra .= "/Users/rpage/Desktop/PDFs/" . $filename . "\t$pdf\n";
}
file_put_contents(dirname(__FILE__) . '/extra.txt', $extra);

echo "--- pdf.sql ---\n";
file_put_contents(dirname(__FILE__) . '/pdf.sql', join("\n", $sqls));




?>