<?php

// http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200201006&journal_id=jtsb_cn

// guihaia

require_once(dirname(dirname(__FILE__)) . '/lib.php');

require_once(dirname(dirname(__FILE__)) . '/simplehtmldom_1_5/simple_html_dom.php');



$ids = array();

for($year = 2015; $year < 2016; $year++)
{
	for($issue = 1; $issue < 7; $issue++)
	{
		for($article = 1; $article < 20; $article++)
		{
			$ids[] = $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 2, STR_PAD_LEFT, '0');
			//$ids[] = $year . 'Z1' . str_pad($article, 3, STR_PAD_LEFT, '0');
		}
	}
}

// One record
//$ids=array(); $ids[]='200504002';

//$ids = array(); $ids[] = '20090201';

//$ids = array(); $ids[] = '201501001';


$count = 0;

foreach ($ids as $id)
{
	$url = 'http://www.guihaia-journal.com/ch/reader/view_abstract.aspx?file_no=' . $id . '&flag=1';
	
	echo "-- $url\n";
	
	
	//echo $h;
	//echo "--------\n\n";

	$html = get($url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
	
	$guid = $url;
	$issn = '1000-3142';

	$keys = array();
	$values = array();

	$multilingual_keys = array();
	$multilingual_values = array();
	
	$keys[] = 'url';
	$values[] = '"' . $url . '"';
	
	
	
	// regex
	// PDF
	// <a href="create_pdf.aspx?file_no=20090201&amp;flag=1&amp;year_id=2009&amp;quarter_id=2">【下载PDF全文】</a>
	if (preg_match('/<a href="(?<pdf>create_pdf.aspx\?file_no=(.*))">/Uu', $html, $m))
	{
		$keys[] = 'pdf';
		$values[] = '"' . addcslashes('http://www.guihaia-journal.com/ch/reader/' . $m['pdf'], '"') . '"';
	}
	
	// Citation
	// <span id="test">韦毅刚, 王文采.广西楼梯草属三新种和一新变种[J].广西植物,2009,(2):143-148</span>
	if (preg_match('/<span id="test">(.*)(?<year>[0-9]{4}),((?<volume>\d+))?\((?<issue>\d+)\):(?<spage>\d+)(-(?<epage>\d+))<\/span>/Uu', $html, $m))
	{
		//print_r($m);
		//exit();
		
		$keys[] = 'year';
		$values[] = '"' . addcslashes($m['year'], '"') . '"';
		
		if ($m['volume'] != '')
		{
			$keys[] = 'volume';
			$values[] = '"' . addcslashes($m['volume'], '"') . '"';		
		}
	}
	
	// DOI
	// <span id="DOI"><a href="http://dx.doi.org/10.11931/guihaia.gxzw201312018">10.11931/guihaia.gxzw201312018</a></span>
	if (preg_match('/<span id="DOI"><a href=\'http:\/\/dx.doi.org\/(?<doi>.*)\'>/Uu', $html, $m))
	{
		$keys[] = 'doi';
		$values[] = '"' . addcslashes($m['doi'], '"') . '"';	
		
		$guid = $m['doi'];
	}
		
	// Authors
	// <span id="test">王文采；.广西苦苣苔科一新属[J].广西植物,1983,(1):1-6</span>
	if (preg_match('/<span id="test">(?<authors>.*)\.(.*)<\/span>/Uu', $html, $m))
	{
	
		$authors = $m['authors'];
		$authors = preg_replace('/；/u', '', $authors);
		$authors = str_replace(',', ';', $authors);
		
		$keys[] = 'authors';
		$values[] = '"' . addcslashes($authors, '"') . '"';		
						
		$language = 'zh';

		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"authors"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
	
		$kk[] = 'value';
		$vv[] = '"' . addcslashes($authors, '"') . '"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;			
		
	}
	
	// English title
	if (preg_match('/<span id="EnTitle">(?<title>.*)<\/span>/Uu', $html, $m))
	{
						
		$language = 'en';

		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"title"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
	
		$kk[] = 'value';
		$vv[] = '"' . addcslashes($m['title'], '"') . '"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;			
		
	}
	
	

	// title
	$k = array(
		'citation_journal_title', 
		'citation_authors',
		'citation_author',
		'citation_title',
		'citation_doi',
		'citation_abstract_html_url',
		'citation_pdf_url',
		'citation_firstpage',
		'citation_lastpage',
		'citation_issn',
		'citation_volume',
		'citation_issue',
		'citation_publication_date',
		'citation_date',
		'Description'
	);

	$translate = array(
		'citation_journal_title' => 'journal',
		'citation_authors' => 'authors',
		'citation_author' => 'authors',
		'citation_title' => 'title',
		'citation_doi' => 'doi',
		'citation_abstract_html_url' => 'url',
		'citation_pdf_url' => 'pdf',
		'citation_firstpage' => 'spage',
		'citation_lastpage' => 'epage',
		'citation_issn' => 'issn',
		'citation_volume' => 'volume',
		'citation_issue' => 'issue',
		'citation_publication_date' => 'date',
		'citation_date' => 'year',
		'Description' => 'abstract'
	);	

	// <meta name="citation_title" content="广西楼梯草属三新种和一新变种">

	foreach ($k as $cite_key)
	{
		//if (preg_match_all('/<meta\s+name="' . $cite_key . '"\s+xml:lang="(?<lang>[a-z]{2})"\s+content="(?<' . $cite_key . '>.*)"\s*\/>/Uu', $html, $m))
		//{}
		
		$pattern = '/<meta\s+name="' . $cite_key . '"\s+content="(?<' . $cite_key . '>.*)"\/>/Uu';
		
		//echo $pattern . "\n";

		if (preg_match($pattern, $html, $m))
		{
			//print_r($m);
	
			if (trim($m[$cite_key]) != '')
			{
			
				// special handling 
				switch ($translate[$cite_key])
				{
					case 'authors':
					case 'year':
						// eat this as it's not publication date
						break;
				
					default:
						$keys[] = $translate[$cite_key];
						$values[] = '"' . addcslashes($m[$cite_key], '"') . '"';
		
						if ($guid == '')
						{
							if ($translate[$cite_key] == 'url')
							{
								$guid = $m[$cite_key];
							}
						}
						if ($translate[$cite_key] == 'doi')
						{
							$guid = $m[$cite_key];
						}
		
		
						if (preg_match('/\p{Han}+/u', $m[$cite_key]))
						{
							$kk = array();
							$vv = array();
							$kk[] = "`key`";
							$vv[] = '"' . $translate[$cite_key] . '"';

							$kk[] = 'language';
							$vv[] = '"zh"';
				
							$kk[] = 'value';
							$vv[] = '"' . addcslashes($m[$cite_key], '"') . '"';

							$multilingual_keys[] = $kk;
							$multilingual_values[] = $vv;
						}
						break;
				}
			}	
		}		
	
	}
		
	$keys[] = 'guid';
	$values[] = '"' . $guid . '"';

	$keys[] = 'issn';
	$values[] = '"' . $issn . '"';
	
	/*
	print_r($keys);
	print_r($values);
	
	print_r($multilingual_keys);
	print_r($multilingual_values);
	
	*/
	
	// populate from scratch
	if (1 && count($keys) > 4) // in_array($reference->journal->volume, array(26,27))) 
	{
		$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
			. join(',', $values) . ');';
		echo $sql . "\n";
	}
	
	$n = count($multilingual_keys);
	for($i =0; $i < $n; $i++)
	{
		$multilingual_keys[$i][] = 'guid';
		$multilingual_values[$i][] = '"' . $guid . '"';

		$sql = 'REPLACE INTO multilingual(' . join(',', $multilingual_keys[$i]) . ') values('
			. join(',', $multilingual_values[$i]) . ');';
		echo $sql . "\n";
	}	
	

	
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
	}
	
	
}

?>