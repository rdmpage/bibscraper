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

$base_url = 'http://med.wanfangdata.com.cn/Paper/Detail?id=';
$base_url = 'http://med.wanfangdata.com.cn/Paper/Detail/';

$periodical = 'PeriodicalPaper_ynzwyj';
$year = 1999;
$issue = 1;
$article = 1;

$periodical = 'PeriodicalPaper_ynzwyj';
$year = 1999;
$issue = 1;
$article = 1;


$periodical = 'PeriodicalPaper_dwxyj';
$year = 1999;
$issue = 1;
$article = 1;



$ids = array();

for($year = 2015; $year < 2016; $year++)
{
	//for($issue = 1; $issue < 7; $issue++)
	for($issue = 1; $issue < 7; $issue++)
	{
		for($article = 1; $article < 20; $article++)
		{
			$ids[] = $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 3, STR_PAD_LEFT, '0');
		}
	}
}

// One record
//$ids=array(); $ids[]='200504002';

$count = 0;

foreach ($ids as $id)
{
	// Fetch
	//$url = $base_url . $periodical . $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 3, STR_PAD_LEFT, '0') . '.aspx';


	//$url = 'http://e.wanfangdata.com.hk/zh-TW/d/Periodical_zxxb200501002.aspx';
	
	//$url = 'http://e.wanfangdata.com.hk/zh-TW/d/Periodical_zxxb' . $id . '.aspx';
	//echo $url . "\n";
	
	
	$url = 'http://med.wanfangdata.com.cn/Paper/Detail?id=PeriodicalPaper_ynzwyj' . $id;
	
	
	
	$url = 'http://med.wanfangdata.com.cn/Paper/Detail/PeriodicalPaper_dwxyj' . $id;

	$url = 'http://med.wanfangdata.com.cn/Paper/Detail/PeriodicalPaper_rdyrdzwxb' . $id;
	
	
	echo "-- $url\n";
	
	//$h = file_get_contents('med.html');
	
	//echo $h;
	//echo "--------\n\n";

	$h = get($url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
	
	//echo $h;
	
	if ($h != '')
	{

		$html = str_get_html($h);
		
		//echo $html;

		$guid = '';

		$keys = array();
		$values = array();

		$multilingual_keys = array();
		$multilingual_values = array();

		$have_authors = false;
		$have_abstract = false;
		
		$table = $html->find('table[class=srhTb1]');
//		$table = $table->find('col');

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
					// MED				
					case '作 者':		
						//echo "\n|$v|\n";		
						$v = preg_replace('/\(.*\)/Uu', '', $v);
						$v = preg_replace('/\s\s+/Uu', '', $v);
						
						$parts = explode("；", $v);
						
					
						$a = array();
						foreach ($parts as $p)
						{
							if ($p != '')
							{
								$a[] = trim($p);
							}
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
						$vv[] = '"zh"';
							
						$kk[] = 'value';
						$vv[] = '"' . addcslashes($authors, '"') . '"';
	
						$multilingual_keys[] = $kk;
						$multilingual_values[] = $vv;										
						break;
				

					// MED
					case '刊 名':
					
						echo "-- $v\n";
						// 1999年21卷01期&nbsp;11-24
						// 1999年21卷01期&nbsp;11-24
						// 2014年01期</a>&nbsp;1-11页&nbsp;
						if (preg_match('/(?<year>[0-9]{4})年(?<volume>\d+)卷(?<issue>\d+)期&nbsp;(?<spage>\d+)(-(?<epage>\d+))?/', $v, $m))
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
								$values[] = preg_replace('/^0/', '', $m['issue']);					
							}
					
							$keys[] = 'spage';
							$values[] = $m['spage'];
					
							if ($m['epage'] != '')
							{
								$keys[] = 'epage';
								$values[] = $m['epage'];					
							}
						}
						
						// 2014年01期</a>&nbsp;1-11页&nbsp;
						// 2014年01期&nbsp;1-11页&nbsp;
						// (?<year>[0-9]{4})年(?<issue>\d+)期(<\/a>)?&nbsp;(?<spage>\d+)(-(?<epage>\d+))?
						if (preg_match('/(?<year>[0-9]{4})年(?<issue>\d+)期(<\/a>)?&nbsp;(?<spage>\d+)(-(?<epage>\d+))?/u', $v, $m))
						{
							$keys[] = 'year';
							$values[] = $m['year'];
							
					
							if ($m['issue'] != '')
							{
								$keys[] = 'issue';
								$values[] = preg_replace('/^0/', '', $m['issue']);					
							}
					
							$keys[] = 'spage';
							$values[] = $m['spage'];
					
							if ($m['epage'] != '')
							{
								$keys[] = 'epage';
								$values[] = $m['epage'];					
							}
						}

						// 云南植物研究&nbsp;                  1999
						if (preg_match('/^(?<journal>.*)&nbsp;\s+(?<year>[0-9]{4})/', $v, $m))
						{
							$journal = $m['journal'];
							if (preg_match('/\p{Han}+/u', $journal))
							{
								$kk = array();
								$vv = array();
								$kk[] = "`key`";
								$vv[] = '"journal"';

								$kk[] = 'language';
								$vv[] = '"zh"';
							
								$kk[] = 'value';
								$vv[] = '"' . addcslashes($journal, '"') . '"';
	
								$multilingual_keys[] = $kk;
								$multilingual_values[] = $vv;										
							}
						}

						break;			
					
					// MED
					case 'DOI号':
						$keys[] = 'doi';
						$values[] = '"' . addcslashes($v, '"') . '"';
						
						$guid = $v;
						break;
					
				
					// MED
					case '英文期刊名':
						$keys[] = 'journal';
						$values[] = '"' . addcslashes($v, '"') . '"';
					
						$language = 'en';
				
						if (preg_match('/\p{Han}+/u', $v))
						{
							$language = 'zh';
						}
					
					
						$kk = array();
						$vv = array();
						$kk[] = "`key`";
						$vv[] = '"journal"';

						$kk[] = 'language';
						$vv[] = '"' . $language . '"';
							
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

		// title
		$h4 = $html->find('h4');
		foreach ($h4 as $h4title)
		{
			$title = $h4title->plaintext;
			
			$keys[] = 'title';
			$values[] = '"' . addcslashes($title, '"') . '"';
			
			$language = 'en';
		
			if (preg_match('/\p{Han}+/u', $title))
			{
				$language = 'zh';
			}
			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"title"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
				
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($title, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;
			
	
		}

		// abstract
		$para = $html->find('p[class=prvTXT]');
		foreach ($para as $p)
		{
			$abstract = $p->plaintext;
			
			$keys[] = 'abstract';
			$values[] = '"' . addcslashes($abstract, '"') . '"';
			
			$language = 'en';
		
			if (preg_match('/\p{Han}+/u', $abstract))
			{
				$language = 'zh';
			}
			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"abstract"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
				
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($abstract, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;
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
		
		$keys[] = 'issn';
		//$values[] = '"0253-2700"';
		//$values[] = '"0254-5853"';
		$values[] = '"1005-3395"';


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