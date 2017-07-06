<?php

// Generate code to fetch PDFs
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/ris.php');
require_once(dirname(__FILE__) . '/utils.php');

$pdfs = array();


function get_pdf($reference)
{
	global $pdfs;
	
	print_r($reference);
	
	if (isset($reference->pdf))
	{
		$pdfs[] = $reference->pdf;
	}
}


$filename = '';
if ($argc < 2)
{
	echo "Usage: import.php <RIS file> <mode>\n";
	exit(1);
}
else
{
	$filename = $argv[1];
}


$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

import_ris_file($filename, 'get_pdf');


echo "--- list ---\n";
foreach ($pdfs as $pdf)
{
	echo "$pdf\n";
}


echo "--- curl fetch.sh ---\n";
foreach ($pdfs as $pdf)
{
	$filename = basename($pdf);
	if (!preg_match('/\.pdf$/', $filename))
	{
		$filename .= '.pdf';
	}

	echo "curl '$pdf' > '" . $filename . "'\n";
}

echo "--- extra.txt ---\n";
foreach ($pdfs as $pdf)
{
	$filename = basename($pdf);
	if (!preg_match('/\.pdf$/', $filename))
	{
		$filename .= '.pdf';
	}


	echo "/Users/rpage/Desktop/PDFs/" . $filename . "\t$pdf\n";
}


?>