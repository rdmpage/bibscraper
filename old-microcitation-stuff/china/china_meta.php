<?php

require_once(dirname(__FILE__) . '/lib.php');



$issue_url = 'http://www.whzwxyj.cn/EN/volumn/volumn_1172.shtml';

$html = get($issue_url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');

if (preg_match_all('/href="..(?<url>\/abstract\/abstract\d+.shtml)"/u', $html, $m))
{
	$article_urls = $m['url'];
	
	foreach ($article_urls as $url)
	{
		$prefix = 'http://www.whzwxyj.cn/CN';
		
		$html = get($prefix . $url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
		
		//echo $html;

		$guid = '';

		$keys = array();
		$values = array();

		$multilingual_keys = array();
		$multilingual_values = array();
		
		// title
		$k = array(
			'citation_journal_title', 
			'citation_authors',
			'citation_title',
			'citation_doi',
			'citation_abstract_html_url',
			'citation_pdf_url',
			'citation_firstpage',
			'citation_lastpage',
			'citation_issn',
			'citation_volume',
			'citation_issue',
			'citation_publication_date'
		);
	
		$translate = array(
			'citation_journal_title' => 'journal',
			'citation_authors' => 'authors',
			'citation_title' => 'title',
			'citation_doi' => 'doi',
			'citation_abstract_html_url' => 'url',
			'citation_pdf_url' => 'pdf',
			'citation_firstpage' => 'spage',
			'citation_lastpage' => 'epage',
			'citation_issn' => 'issn',
			'citation_volume' => 'volume',
			'citation_issue' => 'issue',
			'citation_publication_date' => 'date'
		);	
		
		if (preg_match('/<a href="(.*)\/article\/showTenYearVolumnDetail\.do\?nian=(?<year>[0-9]{4})"/Uu', $html, $m))
		{
			$keys[] = 'year';
			$values[] = '"' . $m['year'] . '"';		
		}
	
		foreach ($k as $cite_key)
		{
			if (preg_match_all('/<meta\s+name="' . $cite_key . '"\s+xml:lang="(?<lang>[a-z]{2})"\s+content="(?<' . $cite_key . '>.*)"\s*\/>/Uu', $html, $m))
			{
				//print_r($m);
				
				$n = count($m[$cite_key]);
				for ($i = 0; $i < $n; $i++)
				{
					if ($i == 0)
					{
						$keys[] = $translate[$cite_key];
						$values[] = '"' . addcslashes($m[$cite_key][$i], '"') . '"';
					}
					
					$language = $m['lang'][$i];
					if ($language == 'cn')
					{
						$language = 'zh';
					}
					
					$kk = array();
					$vv = array();
					$kk[] = "`key`";
					$vv[] = '"' . $translate[$cite_key] . '"';

					$kk[] = 'language';
					$vv[] = '"' . $language . '"';
				
					$kk[] = 'value';
					$vv[] = '"' . addcslashes($m[$cite_key][$i], '"') . '"';

					$multilingual_keys[] = $kk;
					$multilingual_values[] = $vv;
				}
				
				
			}
		
			if (preg_match('/<meta\s+name="' . $cite_key . '"\s+content="(?<' . $cite_key . '>.*)"\s*\/>/Uu', $html, $m))
			{
				//print_r($m);
				
				if (trim($m[$cite_key]) != '')
				{
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
					
					
				}				
				
			}
		}
		
		$keys[] = 'guid';
		$values[] = '"' . $guid . '"';
		
		
		if (1)  
		{
			$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
				. join(',', $values) . ');';
			echo $sql . "\n";
		}
		
		
		//print_r($multilingual_key);
		//print_r($multilingual_values);
		
		$n = count($multilingual_keys);
		for($i =0; $i < $n; $i++)
		{
			$multilingual_keys[$i][] = 'guid';
			$multilingual_values[$i][] = '"' . $guid . '"';

			$sql = 'REPLACE INTO multilingual(' . join(',', $multilingual_keys[$i]) . ') values('
				. join(',', $multilingual_values[$i]) . ');';
			echo $sql . "\n";
		}		
	}
}	
			

?>