<?php

require_once(dirname(__FILE__) . '/lib.php');

$key_map = array(
	'T' => 'title',
	'J' => 'journal',
	'A' => 'authors',
	'V' => 'volume',
	'N' => 'issue',
	'D' => 'year',
	'8' => 'date',
	'X' => 'abstract',
	'R' => 'doi',
	'U' => 'url'
	);


$base_url = 'http://bbr.nefu.edu.cn/CN/article/getTxtFile.do?fileType=EndNote&id=';
$issn = '1673-5102';

$start = 120;
$end = 200;
$start = 400;
$end = 1000;
$start = 1000;
$end = 1500;


for ($id = $start; $id < $end; $id++)
{

	$url = $base_url . $id;

	$endnote = get($url);
	

	$lines = explode("\n", $endnote);
	//echo count($lines);
	
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
						if (preg_match('/(?<spage>\d+)-(?<epage>\d+)/', $v, $m))
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
					
						if (preg_match('/^(?<prefix>.*)abstract\/article_(?<id>\d+)\.shtml/', $v, $m))
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
				
			
					case 'T':
						$key = 'title';
						break;
			
				
					default:
						if (isset($key_map[$k]))
						{
							$key = $key_map[$k];
					
							if (preg_match('/\p{Han}+/u', $v))
							{
								$kk = array();
								$vv = array();
								$kk[] = "`key`";
								$vv[] = '"' . $key . '"';

								$kk[] = 'language';
								$vv[] = '"cn"';
												
								$kk[] = 'value';
								$vv[] = '"' . addcslashes($v, '"') . '"';
						
								$multilingual_keys[] = $kk;
								$multilingual_values[] = $vv;
						
							}
						}
						break;
				
				
				}
		
				if ($key != '')
				{
					$keys[] = $key;
					$values[] = '"' . addcslashes($v, '"') . '"';
				}
	
			}
		}

		//print_r($keys);
		//print_r($values);

		//print_r($multilingual_keys);
		//print_r($multilingual_values);
	
		$keys[] = 'issn';
		$values[] = '"' . $issn . '"';
	

		if ($guid == '')
		{	
			$guid = md5(join('', $values));
		}
		$keys[] = 'guid';
		$values[] = '"' . $guid . '"';


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
	
	if (($id % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
	}
		
		

}


?>