<?php

require_once('lib.php');


$urls = array(
'http://www.redalyc.org/toc.oa?id=574&numero=10334',
'http://www.redalyc.org/toc.oa?id=574&numero=10848',
'http://www.redalyc.org/toc.oa?id=574&numero=11548',
'http://www.redalyc.org/toc.oa?id=574&numero=12072',
'http://www.redalyc.org/toc.oa?id=574&numero=12073',
'http://www.redalyc.org/toc.oa?id=574&numero=12083',
'http://www.redalyc.org/toc.oa?id=574&numero=12477',
'http://www.redalyc.org/toc.oa?id=574&numero=15131',
'http://www.redalyc.org/toc.oa?id=574&numero=15475',
'http://www.redalyc.org/toc.oa?id=574&numero=15694',
'http://www.redalyc.org/toc.oa?id=574&numero=18937',
'http://www.redalyc.org/toc.oa?id=574&numero=19276',
'http://www.redalyc.org/toc.oa?id=574&numero=21427',
'http://www.redalyc.org/toc.oa?id=574&numero=23325',
'http://www.redalyc.org/toc.oa?id=574&numero=23330',
'http://www.redalyc.org/toc.oa?id=574&numero=24406',
'http://www.redalyc.org/toc.oa?id=574&numero=24816',
'http://www.redalyc.org/toc.oa?id=574&numero=25568',
'http://www.redalyc.org/toc.oa?id=574&numero=25775',
'http://www.redalyc.org/toc.oa?id=574&numero=2595',
'http://www.redalyc.org/toc.oa?id=574&numero=2596',
'http://www.redalyc.org/toc.oa?id=574&numero=2597',
'http://www.redalyc.org/toc.oa?id=574&numero=2598',
'http://www.redalyc.org/toc.oa?id=574&numero=2610',
'http://www.redalyc.org/toc.oa?id=574&numero=2611',
'http://www.redalyc.org/toc.oa?id=574&numero=2612',
'http://www.redalyc.org/toc.oa?id=574&numero=2613',
'http://www.redalyc.org/toc.oa?id=574&numero=2618',
'http://www.redalyc.org/toc.oa?id=574&numero=2619',
'http://www.redalyc.org/toc.oa?id=574&numero=2624',
'http://www.redalyc.org/toc.oa?id=574&numero=2626',
'http://www.redalyc.org/toc.oa?id=574&numero=2646',
'http://www.redalyc.org/toc.oa?id=574&numero=2647',
'http://www.redalyc.org/toc.oa?id=574&numero=2648',
'http://www.redalyc.org/toc.oa?id=574&numero=2649',
'http://www.redalyc.org/toc.oa?id=574&numero=2658',
'http://www.redalyc.org/toc.oa?id=574&numero=2664',
'http://www.redalyc.org/toc.oa?id=574&numero=2667',
'http://www.redalyc.org/toc.oa?id=574&numero=2675',
'http://www.redalyc.org/toc.oa?id=574&numero=2678',
'http://www.redalyc.org/toc.oa?id=574&numero=2701',
'http://www.redalyc.org/toc.oa?id=574&numero=2702',
'http://www.redalyc.org/toc.oa?id=574&numero=2703',
'http://www.redalyc.org/toc.oa?id=574&numero=2704',
'http://www.redalyc.org/toc.oa?id=574&numero=2705',
'http://www.redalyc.org/toc.oa?id=574&numero=2714',
'http://www.redalyc.org/toc.oa?id=574&numero=2716',
'http://www.redalyc.org/toc.oa?id=574&numero=2720',
'http://www.redalyc.org/toc.oa?id=574&numero=2721',
'http://www.redalyc.org/toc.oa?id=574&numero=2722',
'http://www.redalyc.org/toc.oa?id=574&numero=2723',
'http://www.redalyc.org/toc.oa?id=574&numero=2737',
'http://www.redalyc.org/toc.oa?id=574&numero=2738',
'http://www.redalyc.org/toc.oa?id=574&numero=2739',
'http://www.redalyc.org/toc.oa?id=574&numero=27415',
'http://www.redalyc.org/toc.oa?id=574&numero=2762',
'http://www.redalyc.org/toc.oa?id=574&numero=2784',
'http://www.redalyc.org/toc.oa?id=574&numero=2786',
'http://www.redalyc.org/toc.oa?id=574&numero=2791',
'http://www.redalyc.org/toc.oa?id=574&numero=2794',
'http://www.redalyc.org/toc.oa?id=574&numero=2808',
'http://www.redalyc.org/toc.oa?id=574&numero=2814',
'http://www.redalyc.org/toc.oa?id=574&numero=2820',
'http://www.redalyc.org/toc.oa?id=574&numero=2822',
'http://www.redalyc.org/toc.oa?id=574&numero=2828',
'http://www.redalyc.org/toc.oa?id=574&numero=28309',
'http://www.redalyc.org/toc.oa?id=574&numero=2853',
'http://www.redalyc.org/toc.oa?id=574&numero=2860',
'http://www.redalyc.org/toc.oa?id=574&numero=2861',
'http://www.redalyc.org/toc.oa?id=574&numero=2878',
'http://www.redalyc.org/toc.oa?id=574&numero=2880',
'http://www.redalyc.org/toc.oa?id=574&numero=2881',
'http://www.redalyc.org/toc.oa?id=574&numero=2912',
'http://www.redalyc.org/toc.oa?id=574&numero=2913',
'http://www.redalyc.org/toc.oa?id=574&numero=29297',
'http://www.redalyc.org/toc.oa?id=574&numero=30483',
'http://www.redalyc.org/toc.oa?id=574&numero=31269',
'http://www.redalyc.org/toc.oa?id=574&numero=32065',
'http://www.redalyc.org/toc.oa?id=574&numero=32981',
'http://www.redalyc.org/toc.oa?id=574&numero=36204',
'http://www.redalyc.org/toc.oa?id=574&numero=40276',
'http://www.redalyc.org/toc.oa?id=574&numero=4215',
'http://www.redalyc.org/toc.oa?id=574&numero=42729',
'http://www.redalyc.org/toc.oa?id=574&numero=43457',
'http://www.redalyc.org/toc.oa?id=574&numero=5865',
'http://www.redalyc.org/toc.oa?id=574&numero=5871',
'http://www.redalyc.org/toc.oa?id=574&numero=6023',
'http://www.redalyc.org/toc.oa?id=574&numero=6028',
'http://www.redalyc.org/toc.oa?id=574&numero=6459',
'http://www.redalyc.org/toc.oa?id=574&numero=6634',
'http://www.redalyc.org/toc.oa?id=574&numero=6755',
'http://www.redalyc.org/toc.oa?id=574&numero=6761',
'http://www.redalyc.org/toc.oa?id=574&numero=6763',
'http://www.redalyc.org/toc.oa?id=574&numero=6764',
'http://www.redalyc.org/toc.oa?id=574&numero=6773',
'http://www.redalyc.org/toc.oa?id=574&numero=6774',
'http://www.redalyc.org/toc.oa?id=574&numero=6775',
'http://www.redalyc.org/toc.oa?id=574&numero=6776',
'http://www.redalyc.org/toc.oa?id=574&numero=6782',
'http://www.redalyc.org/toc.oa?id=574&numero=6783',
'http://www.redalyc.org/toc.oa?id=574&numero=6784',
'http://www.redalyc.org/toc.oa?id=574&numero=6785',
'http://www.redalyc.org/toc.oa?id=574&numero=6789',
'http://www.redalyc.org/toc.oa?id=574&numero=6792',
'http://www.redalyc.org/toc.oa?id=574&numero=6793',
'http://www.redalyc.org/toc.oa?id=574&numero=6794',
'http://www.redalyc.org/toc.oa?id=574&numero=6795',
'http://www.redalyc.org/toc.oa?id=574&numero=6796',
'http://www.redalyc.org/toc.oa?id=574&numero=6832',
'http://www.redalyc.org/toc.oa?id=574&numero=6834',
'http://www.redalyc.org/toc.oa?id=574&numero=7382',
'http://www.redalyc.org/toc.oa?id=574&numero=7752',
'http://www.redalyc.org/toc.oa?id=574&numero=8354',
'http://www.redalyc.org/toc.oa?id=574&numero=9779'
);

$count = 0;

foreach ($urls as $url)
{
	$h = get($url);
	
	// <a href="articulo.oa?id=57415694001"><span class="articulo-fasciculo-nuevo texto-gris-bold-12">LA FAMILIA BURSERACEAE EN EL ESTADO DE AGUASCALIENTES, MÉXICO</span></a>
	preg_match_all('/<a href="(?<url>articulo.oa\?id=\d+)">/Uu', $h, $articles);
	
	$articles = array_unique($articles['url']);
	
	//print_r($articles);exit();
	
	foreach ($articles as $article)
	{
		$html = get('http://www.redalyc.org/' . $article);

		//$html = file_get_contents('red.html');

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
	
		$guid = '';
		
		$keys = array();
		$values = array();

		$multilingual_keys = array();
		$multilingual_values = array();
		
	
		$authors = array();

		foreach ($k as $cite_key)
		{		
			$pattern = '/<meta\s+name="' . $cite_key . '"\s+content="(?<' . $cite_key . '>.*)"\s*\/>/Uu';
		
			//echo $pattern . "\n";

			if (preg_match_all($pattern, $html, $m))
			{
				//print_r($m);
				foreach ($m[$cite_key] as $value)
				{			
					$key = $translate[$cite_key];
					$value = trim(html_entity_decode($value));
					$value = preg_replace('/\s\s+/u', ' ', $value);
	
					if ($value != '')
					{
						switch ($key)
						{
							case 'authors':
								$authors[] = $value;
								break;
				
							case 'issue':					
								$keys[] = 'volume';
								$values[] = '"' . addcslashes($value, '"') . '"';
								break;

							case 'date':					
								$keys[] = 'year';
								$values[] = '"' . addcslashes($value, '"') . '"';
								break;

							case 'url':					
								$keys[] = 'url';
								$values[] = '"' . addcslashes($value, '"') . '"';
						
								$guid = $value;
								break;

							default:
								$keys[] = $key;
								$values[] = '"' . addcslashes($value, '"') . '"';
								break;
						}
			
					}
				}
			}
		}
	
		if (count($authors) > 0)
		{
			$keys[] = 'authors';
			$values[] = '"' . addcslashes(join(";", $authors), '"') . '"';	
		}
	
		// <meta lang="en" name="description" content="New taxa of the Lonchocarpus genus from Mexico and Mesoamerica (Leguminosae, Papilionoideae, Millettieae) are described and illustrated. Of the 19 recognized..." />
		if (preg_match_all('/<meta lang="(?<language>\w+)" name="description" content="(?<content>.*)"\s+\/>/Uu', $html, $m))
		{
			$n = count($m[0]);
		
			for($i = 0; $i < $n; $i++)
			{
				$kk = array();
				$vv = array();
				$kk[] = "`key`";
				$vv[] = '"abstract"';

				$kk[] = 'language';
				$vv[] = '"' . $m['language'][$i] . '"';

				$kk[] = 'value';
		
				$value = html_entity_decode($m['content'][$i]);
		
				$vv[] = '"' . addcslashes($value, '"') . '"';

				$multilingual_keys[] = $kk;
				$multilingual_values[] = $vv;
			}
		}
	
		/*
		print_r($keys);
		print_r($values);
	
		print_r($multilingual_keys);
		print_r($multilingual_values);
		*/
		
		$keys[] = 'guid';
		$values[] = '"' . $guid . '"';

	
	
		$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
			. join(',', $values) . ');';
		echo $sql . "\n";
	
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
	
	
}	
	
	
?>
