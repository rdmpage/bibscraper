<?php

require_once('lib.php');

$issn = '1000-6567';

for ($year = 1991; $year < 1992; $year++)
//for ($year = 2000; $year < 2002; $year++)
{
	for ($issue = 1; $issue < 5; $issue++)
	{
		$url = 'http://www.cqvip.com/QK/97864X/' . $year .  str_pad($issue, 2, STR_PAD_LEFT, '0') . '/';
		
		echo "-- $url\n";
		
		$html = get($url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
	
		if (preg_match_all('/<a href="(?<url>\/QK\/97864X\/\d+\/\d+.html)" target="_blank" class="artics">/Uu', $html, $m))
		{
			$urls = $m['url'];
			foreach ($urls as $url)
			{
				$url = 'http://www.cqvip.com' . $url;
			
				echo "-- $url\n";
	
	
	



				$html = get($url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
		
				//echo $html;

				$guid = $url;

				$keys = array();
				$values = array();

				$multilingual_keys = array();
				$multilingual_values = array();
		
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
					
					
					
						}				
				
					}
				}
		
				$keys[] = 'guid';
				$values[] = '"' . $guid . '"';


				$keys[] = 'issn';
				$values[] = '"' . $issn . '"';
		
				if (0)
				{
					$n = count($keys);
					
					$q = array();
					
					$epage = '';
					
					for ($i = 0; $i < $n; $i++)
					{
						if (in_array($keys[$i] , array('issn', 'volume', 'issue', 'spage')))
						{
							$q[] = $keys[$i] . '=' . $values[$i];
						}
						if ($keys[$i] == 'epage')
						{
							$epage = $values[$i];
						}
					}
					
					if ($epage != '' && (count($q) == 4))
					{
						$sql = 'UPDATE publications SET epage=' . $epage . ' WHERE ' . join(" AND ", $q) . ';';
						echo $sql . "\n";
					}
					
				}
		
				if (1)  
				{
					$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
						. join(',', $values) . ');';
					echo $sql . "\n";
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
		}
	}
}
?>