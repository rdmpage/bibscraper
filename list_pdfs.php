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

print_r($pdfs);


foreach ($pdfs as $pdf)
{
	echo "curl '$pdf' > " . basename($pdf) . "\n";
}

foreach ($pdfs as $pdf)
{
	echo "/Users/rpage/Desktop/PDFs/" . basename($pdf) . "\t$pdf\n";
}


?>