<?php

// Fetch PDFs for URLs

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');


$dois = array('10.4102/abc.v32i1.462');

$dois=array('10.4102/abc.v32i2.478');


$urls = array();

foreach ($dois as $doi)
{
	$urls[] = 'http://dx.doi.org/' . $doi;
}

$count = 1;

foreach ($urls as $url)
{
	$pdf = '';

	$html = get($url);

	$dom = str_get_html($html);

	$metas = $dom->find('meta');

	/*
	foreach ($metas as $meta)
	{
		echo $meta->name . " " . $meta->content . "\n";
	}
	*/		

	foreach ($metas as $meta)
	{
		switch ($meta->name)
		{
//			case 'citation_pdf_url':
			case 'fulltext_pdf':
				$pdf =  $meta->content;
				break;

			default:
				break;
		}
	}	
	
	
	if ($pdf != '')
	{
		echo 'UPDATE publications SET pdf="' . $pdf . '" WHERE guid="' . $doi . '";' . "\n";
	}	


	// Give server a break every 10 items
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
		usleep($rand);
	}

}


?>