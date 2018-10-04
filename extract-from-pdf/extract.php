<?php

require_once(dirname(dirname(__FILE__)) . '/ris.php');


//----------------------------------------------------------------------------------------

/*
// load page map

$data = array();

$pdfs = array();
$sql = '';

$count = 0;

$filename = 'pages.tsv';
$file_handle = fopen($filename, "r");
		
while (!feof($file_handle)) 
{
	$row = trim(fgets($file_handle));
	
	if ($count != 0)
	{
	
		$parts = explode("\t", $row);
	
		if (!isset($data[$parts[0]]))
		{
			$data[$parts[0]] = array();
		}
		if (!isset($data[$parts[0]][$parts[1]]))
		{
			$data[$parts[0]][$parts[1]] = array();
		}
		$data[$parts[0]][$parts[1]][$parts[2]] = $parts[3];
	}
	
	$count++;
}

print_r($data);	
*/



//----------------------------------------------------------------------------------------
// Convert page number in article to physical page in PDF
function map_page_number_to_pdf ($reference)
{
	$pdf_page = $reference->spage;

	return $pdf_page;

}

//----------------------------------------------------------------------------------------
// Which PDF has the article?
function map_article_to_source_pdf ($reference)
{
	$pdf_filename = '';
	
	return $pdf_filename;
}

//----------------------------------------------------------------------------------------
// Generate a PII-like standard name for the article PDF
function article_pdf_name ($reference)
{
	$pdf_name = 'S' . $reference->issn 
		. $reference->year 
		. str_pad($reference->volume, 4, '0', STR_PAD_LEFT) 
		. str_pad($reference->spage, 5, '0', STR_PAD_LEFT) 
		. '.pdf';
	return $pdf_name;
}


//----------------------------------------------------------------------------------------
function import($reference)
{
	global $config;
	
	$force = true;
	$force = false;
		
	print_r($reference);
	
	if (isset($reference->volume)
		//&& isset($reference->issue)
		&& isset($reference->spage)
		&& isset($reference->year)
		&& isset($reference->issn)
		)
	{
		$pdf_filename = map_article_to_source_pdf($reference);
		
		$article_pdf_filename = article_pdf_name($reference);
		
		if (file_exists($article_pdf_filename) && !$force)
		{
		}
		else
		{		
			$from = map_page_number_to_pdf($reference);
			
			if (isset($reference->epage))
			{
				$to = $from + ($reference->epage - $reference->spage);
			}
			else
			{
				$to = $from;
			}

			$command = 'gs -sDEVICE=pdfwrite -dNOPAUSE -dBATCH -dSAFER '
				. ' -dFirstPage=' . $from . ' -dLastPage=' . $to
				. ' -sOutputFile=\'' . $article_pdf_filename . '\' \'' .  $pdf_filename . '\'';

			echo $command . "\n";

			system($command);
	
			// pdf_add_xmp($reference, $article_pdf);
		}
	}
}


$filename = '';
if ($argc < 2)
{
	echo "Usage: extract.php <RIS file>\n";
	exit(1);
}
else
{
	$filename = $argv[1];
}

$file = @fopen($filename, "r") or die("couldn't open $filename");
fclose($file);

import_ris_file($filename, 'import');


?>