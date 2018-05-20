<?php

require_once(dirname(__FILE__) . '/lib.php');

require('simplehtmldom_1_5/simple_html_dom.php');


/*
$base_url = 'http://med.wanfangdata.com.cn/Paper/Detail?id=';



$periodical = 'PeriodicalPaper_jscyyxkcxb';
$year = 2015;
$issue = 1;
$article = 10;


//
$url = $base_url . $periodical . $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 3, STR_PAD_LEFT, '0');
*/

$base_url = 'http://e.wanfangdata.com.hk/zh-TW/d/';

$periodical = 'Periodical_zxxb';
$year = 2005;
$issue = 1;
$article = 1;



$ids = array();

for($year = 2014; $year < 2015; $year++)
{
	for($issue = 1; $issue < 3; $issue++)
	{
		for($article = 0; $article < 20; $article++)
		{
			$ids[] = $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 3, STR_PAD_LEFT, '0');
		}
	}
}

//$ids=array();
//$ids[]='201402010';

$count = 0;

foreach ($ids as $id)
{
	// Fetch
	$url = $base_url . $periodical . $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 3, STR_PAD_LEFT, '0') . '.aspx';

	$url = 'http://e.wanfangdata.com.hk/zh-TW/d/Periodical_zxxb200501002.aspx';
	
	$url = 'http://e.wanfangdata.com.hk/zh-TW/d/Periodical_zxxb' . $id . '.aspx';
	//echo $url . "\n";

	$h = get($url);
	
	if ($h != '')
	{

		$html = str_get_html($h);

		$guid = '';

		$keys = array();
		$values = array();

		$multilingual_keys = array();
		$multilingual_values = array();

		$have_authors = false;
		$have_abstract = false;
		
		$table = $html->find('table');

		foreach ($table as $t)
		{
			$rows = $t->find('tr');
			foreach ($rows as $tr)
			{
				$k = '';
		
				$th = $tr->find('th');
				foreach ($th as $cell)
				{
					//echo "|" . $cell->plaintext . "-|";
			
					$k = preg_replace('/^\s+/u', '', $cell->plaintext);
					$k = preg_replace('/\s+$/u', '', $k);
					$k = preg_replace('/：$/u', '', $k);
			
					//echo "k=|$k-|\n";
			
				}

				$td = $tr->find('td');
				foreach ($td as $cell)
				{
					//echo "|" . $cell->plaintext . "|";
			
					$v = trim($cell->plaintext);
					$v = preg_replace('/\s+(&nbsp;)?$/u', '', $v);
			
			
				}
		
				switch ($k)
				{
					case 'ISSN':
						$keys[] = 'issn';
						$values[] = '"' . addcslashes($v, '"') . '"';
						break;
				
					case 'Journal':
						$keys[] = 'journal';
						$values[] = '"' . addcslashes($v, '"') . '"';
						break;
				
					case 'Author':
						$v = preg_replace('/\[\d+\]/u', '', $v);
						$parts = explode("&nbsp;&nbsp;", $v);
				
						$a = array();
						foreach ($parts as $p)
						{
							$a[] = trim($p);
						}
						$authors = join(";", $a);
				
						if (!$have_authors)
						{
							$keys[] = 'authors';
							$values[] = '"' . addcslashes($authors, '"') . '"';
							$have_authors = true;
						}
						
						if ($authors != '')
						{
				
							// multilingual
							$kk = array();
							$vv = array();
							$kk[] = "`key`";
							$vv[] = '"authors"';

							$kk[] = 'language';
							$vv[] = '"en"';
							
							$kk[] = 'value';
							$vv[] = '"' . addcslashes($authors, '"') . '"';
	
							$multilingual_keys[] = $kk;
							$multilingual_values[] = $vv;										
						}				
						break;
				
					case '作者':				
						$v = preg_replace('/\[\d+\]/u', '', $v);
						$parts = explode("&nbsp;&nbsp;", $v);
				
						$a = array();
						foreach ($parts as $p)
						{
							$a[] = trim($p);
						}
						$authors = join(";", $a);
				
						if (!$have_authors)
						{
							$keys[] = 'authors';
							$values[] = '"' . addcslashes($authors, '"') . '"';
							$have_authors = true;
						}
				
						// multilingual
						$kk = array();
						$vv = array();
						$kk[] = "`key`";
						$vv[] = '"authors"';

						$kk[] = 'language';
						$vv[] = '"cn"';
							
						$kk[] = 'value';
						$vv[] = '"' . addcslashes($authors, '"') . '"';
	
						$multilingual_keys[] = $kk;
						$multilingual_values[] = $vv;										
						break;
				
					case '在線出版日期':
						if (preg_match('/(?<year>[0-9]{4})年(?<month>\d+)月(?<day>\d+)日/u', $v, $m))
						{
							$keys[] = 'date';
							$values[]  = '"' . $m['year'] . '-' . str_pad($m['month'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($m['day'], 2, 0, STR_PAD_LEFT) . '"';
						}
						break;
				
					case '年，卷(期)':
						//echo "|$v|\n"; exit();
						// 2014, (2) ,123-129
						// 2005,&nbsp;14(1)&nbsp;,47-52
						if (preg_match('/(?<year>[0-9]{4}),(&nbsp;|\s+)((?<volume>\d+))?(\((?<issue>\d+)\))?(&nbsp;|\s+),(?<spage>\d+)(-(?<epage>\d+))?/', $v, $m))
						{
							$keys[] = 'year';
							$values[] = $m['year'];
							
							if ($m['volume'] != '')
							{
								$keys[] = 'volume';
								$values[] = $m['volume'];					
							}
							
					
							if ($m['issue'] != '')
							{
								$keys[] = 'issue';
								$values[] = $m['issue'];					
							}
					
							$keys[] = 'spage';
							$values[] = $m['spage'];
					
							if ($m['epage'] != '')
							{
								$keys[] = 'epage';
								$values[] = $m['epage'];					
							}
						}
						break;				
				
					case '期  刊':
						$kk = array();
						$vv = array();
						$kk[] = "`key`";
						$vv[] = '"journal"';

						$kk[] = 'language';
						$vv[] = '"cn"';
							
						$kk[] = 'value';
						$vv[] = '"' . addcslashes($v, '"') . '"';
	
						$multilingual_keys[] = $kk;
						$multilingual_values[] = $vv;										
						break;				
				
					default:
						break;
				}
					
		
		
				//echo "\n------------------------------------\n";
			}
		}

		// DOI
		/*
		<dl id="doi_dl" class="abstract_dl">
				<dt>doi：</dt>
				<dd><a href="http://dx.doi.org/10.3969%2fj.issn.1005-9628.2005.01.011" target="_blank">10.3969/j.issn.1005-9628.2005.01.011</a></dd>
			</dl>
		*/
		$dl = $html->find('dl[id=doi_dl]');
		foreach ($dl as $d)
		{
			$dd = $d->find('dd');
			foreach ($dd as $cell)
			{
				//echo "|" . $cell->plaintext . "-|";
		
				$keys[] = 'doi';
				$v = trim($cell->plaintext);
				$values[] = '"' . addcslashes($v, '"') . '"';
		
				$guid = $v;
			}	
		}

		// abstract
		$dl = $html->find('dl[class=abstract_dl]');
		foreach ($dl as $d)
		{
			$dd = $d->find('dd');
			foreach ($dd as $cell)
			{
				//echo "|" . $cell->plaintext . "-|";
		
				$v = trim($cell->plaintext);
		
				if (!preg_match('/^10/u', $v))
				{
					if (!$have_abstract)	
					{
						$keys[] = 'abstract';		
						$values[] = '"' . addcslashes($v, '"') . '"';
						$have_abstract = true;
					}
		
					$language = 'en';
				
					if (preg_match('/\p{Han}+/u', $v))
					{
						$language = 'cn';
					}
					$kk = array();
					$vv = array();
					$kk[] = "`key`";
					$vv[] = '"abstract"';

					$kk[] = 'language';
					$vv[] = '"' . $language . '"';
						
					$kk[] = 'value';
					$vv[] = '"' . addcslashes($v, '"') . '"';

					$multilingual_keys[] = $kk;
					$multilingual_values[] = $vv;
				
				}
			}	
		}

		// Title
		/*
		<p id="title0">
									不同冬季氣候條件下的麥田蜘蛛群落結構和優勢種繁殖情況
							</p>*/

		$para = $html->find('p[id=title0]');
		foreach ($para as $p)
		{		
			$keys[] = 'title';
			$v = trim($p->plaintext);
			$values[] = '"' . addcslashes($v, '"') . '"';

			if (preg_match('/\p{Han}+/u', $v))
			{
				$kk = array();
				$vv = array();
				$kk[] = "`key`";
				$vv[] = '"title"';

				$kk[] = 'language';
				$vv[] = '"cn"';
						
				$kk[] = 'value';
				$vv[] = '"' . addcslashes($v, '"') . '"';

				$multilingual_keys[] = $kk;
				$multilingual_values[] = $vv;
			}
	
		}
	
		$para = $html->find('blockquote');
		foreach ($para as $p)
		{		
			$s = $p->find('small');
			foreach ($s as $small)
			{
				$v = trim($small->plaintext);
			
				$language = 'en';	
			
				if (preg_match('/\p{Han}+/u', $v))
				{
					$language = 'cn';
				}
				$kk = array();
				$vv = array();
				$kk[] = "`key`";
				$vv[] = '"title"';

				$kk[] = 'language';
				$vv[] = '"' . $language . '"';
				
				$kk[] = 'value';
				$vv[] = '"' . addcslashes($v, '"') . '"';

				$multilingual_keys[] = $kk;
				$multilingual_values[] = $vv;
			}
		/*
			$keys[] = 'title';
			$v = trim($p->plaintext);
			$values[] = '"' . addcslashes($v, '"') . '"';

			if (preg_match('/\p{Han}+/u', $v))
			{
				$kk = array();
				$vv = array();
				$kk[] = "`key`";
				$vv[] = '"title"';

				$kk[] = 'language';
				$vv[] = '"cn"';
						
				$kk[] = 'value';
				$vv[] = '"' . addcslashes($v, '"') . '"';

				$multilingual_keys[] = $kk;
				$multilingual_values[] = $vv;
			}
		*/
		}	
	
	
		$keys[] ='url';
		$values[] = '"' . addcslashes($url, '"') . '"';
	
		if ($guid == '')
		{
			$guid = $url;
		}
		/*
		if ($guid == '')
		{	
			$guid = md5(join('', $values));
		}
		*/
	
		$keys[] = 'guid';
		$values[] = '"' . $guid . '"';



		//print_r($keys);
		//print_r($values);

		//print_r($multilingual_keys);
		//print_r($multilingual_values);

		// populate from scratch
		if (1) // in_array($reference->journal->volume, array(26,27))) 
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
	}
	
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
	}
	
	
}

?>