<?php

require_once('../lib.php');

/*
%0 Journal Article
%D 2005
%J 植物科学学报
%R                                         
%P 107-110
%V 23
%N 2
%U {http://www.whzwxyj.cn/CN/abstract/article_978.shtml}
%8 2005-04-25
%X 采用染色体压片技术对双二倍体种<I>Cucumis hytivus</I>染色体组间交换重组及其对雄配子育性的影响进行了细胞学研究。找到了该物种染色体组间交换重组的细胞学证据:包括前期的"8"字形、"十"字形结构和中期环状、链状多价体。研究还发现各种可能导致遗传物质不均衡分离的异常结构如染色体桥、染色体滞后、染色体组分离、微核等。染色体组间广泛的交换和重组导致约93%的多分体及部分异常四分体的形成,多分体中大量的畸形小孢子和四分体中遗传物质不平衡的小孢子因不能正常发育而最终形成败育的雄配子,直接影响到<I>Cucumis hytivus</I>育性。%A 刘航;李丹;李绍波
%T 水稻红莲型CMS育性恢复QTL分析
*/
// Parse Endnote for a journal, must supply ISSN, optional $ignore_keys enables us to 
// handle cases where we just want fields that have values in different languages.
function parse_endnote($endnote, $issn = '0000-0000', $ignore_keys = false)
{
	$key_map = array(
		'T' => 'title',
		'J' => 'journal',
		'A' => 'authors',
		'V' => 'volume',
		'N' => 'issue',
		'D' => 'year',
		'8' => 'date',
		'B' => 'date',
		'X' => 'abstract',
		'R' => 'doi',
		'U' => 'url'
		);


	$lines = explode("\n", $endnote);
	
	if (count($lines) > 1)
	{

		//print_r($lines);

		$guid = '';

		$keys = array();
		$values = array();

		$multilingual_keys = array();
		$multilingual_values = array();

		foreach ($lines as $line)
		{
			if (preg_match('/^%(?<key>[A-Z0-9])\s(?<value>.*)$/Uu', $line, $m))
			{
				//print_r($m);
				$k 	= $m['key'];
				$v 	= $m['value'];
		
				$v = preg_replace('/\s+$/u', '', $v);
		
				$key = '';
		
				switch ($k)
				{
					case 'P':
						if (preg_match('/(?<spage>\d+)-(?<epage>\d+)/', $v, $m) && !$ignore_keys)
						{
							$keys[] = 'spage';
							$values[] = $m['spage'];
							$keys[] = 'epage';
							$values[] = $m['epage'];
						}
						break;
			
					case 'R':
						$guid = $v;
						$key = $key_map[$k];
						break;

					case 'U':
						$key = $key_map[$k];
						$v = preg_replace('/\{/u', '', $v);
						$v = preg_replace('/\}/u', '', $v);
					
					
						// http://bbr.nefu.edu.cn/CN/abstract/
						// http://bbr.nefu.edu.cn/CN/abstract/article_1448.shtml
					
						if (preg_match('/^(?<prefix>.*)abstract\/article_(?<id>\d+)\.shtml/', $v, $m) && !$ignore_keys)
						{
							$keys[] = 'pdf';
							$values[] = '"' . $m['prefix'] . 'article/downloadArticleFile.do?attachType=PDF&id=' . $m['id'] . '"';
						}
					
						$v = preg_replace('/article_/u', 'abstract', $v);
					
				
						if ($guid == '')
						{
							$guid = $v;
						}	
						break;
				
							
					default:
						if (isset($key_map[$k]))
						{
							$key = $key_map[$k];
							
							if (in_array($key, array('title', 'journal', 'authors', 'abstract')))
							{
								$language = 'en';
								if (preg_match('/\p{Han}+/u', $v))
								{
									$language = 'zh';
								}
								
								$kk = array();
								$vv = array();
								$kk[] = "`key`";
								$vv[] = '"' . $key . '"';

								$kk[] = 'language';
								$vv[] = '"' . $language . '"';
											
								$kk[] = 'value';
								$vv[] = '"' . addcslashes($v, '"') . '"';
					
								$multilingual_keys[] = $kk;
								$multilingual_values[] = $vv;
							}
						}
						break;
				
				
				}
		
				if (!$ignore_keys)
				{
					if (($key != '') && (trim($v) != ''))
					{
						$keys[] = $key;
						$values[] = '"' . addcslashes($v, '"') . '"';
					}
				}	
			}
		}

		/*
		print_r($keys);
		print_r($values);

		print_r($multilingual_keys);
		print_r($multilingual_values);
		*/
	
		$keys[] = 'issn';
		$values[] = '"' . $issn . '"';
	

		if ($guid == '')
		{	
			$guid = md5(join('', $values));
		}
		$keys[] = 'guid';
		$values[] = '"' . $guid . '"';

		
		if (!$ignore_keys)
		{
			// populate from scratch
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
}


//for ($year = 1983; $year < 2016; $year++)
//for ($year = 2007; $year < 2016; $year++)
for ($year = 2005; $year < 2006; $year++)
{
	$url = 'http://www.whzwxyj.cn/CN/article/showTenYearVolumnDetail.do?nian=' . $year;
	
	echo "-- $url\n";
	
	$h = get($url);
	
	if (preg_match_all('/href="..\/volumn\/volumn_(?<vol>\d+).shtml">/Uu', $h, $volumes))
	{
		foreach ($volumes['vol'] as $vol)
		{
			$url = 'http://www.whzwxyj.cn/CN/volumn/volumn_' . $vol . '.shtml';
			
			$url = 'http://www.whzwxyj.cn/CN/volumn/volumn_1176.shtml';
			
			echo "-- $url\n";
			
			$html = get($url);

			//$html = file_get_contents('植物科学学报.html');

			if (preg_match_all('/attachType=PDF&id=(?<id>\d+)">/Uu', $html, $m))
			{
				foreach ($m[id] as $id)
				{
				
					$id = 757;
					// Chinese
					$url = 'http://www.whzwxyj.cn/CN/article/getTxtFile.do?fileType=EndNote&id=' . $id;	
					$endnote = get($url);
		
					parse_endnote($endnote, '1000-470X');
		
					// English
					$url = 'http://www.whzwxyj.cn/EN/article/getTxtFile.do?fileType=EndNote&id=' . $id;	
					$endnote = get($url);
		
					parse_endnote($endnote, '1000-470X', true);
		
					exit();
				}
			}
		}
	}
}
?>