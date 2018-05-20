<?php

require_once(dirname(dirname(__FILE__)) . '/lib.php');


$key_map = array(
	'VO' => 'volume',
	'IS' => 'issue',
	'YR' => 'year',
	'SN' => 'issn',
	'AB' => 'abstract',
	'DO' => 'doi',
	'LK' => 'url',
	'T1' => 'title',
	'T2' => 'title',
	'A1' => 'authors',
	'JF' => 'journal'
	);

$base_url = 'http://s.wanfangdata.com.cn/Export/Export.aspx';

// Journal of Bamboo Research
$periodical = 'Periodical_zzyjhk';

// Guihaia
$periodical = 'Periodical_gxzw';




for ($year = 2006; $year < 2007; $year++)
{
	for ($issue = 5; $issue < 6; $issue++)
	{
		for ($article = 20; $article < 30; $article++)
		{
			$have = array();
		
			$cookie = 'rs=' . urlencode("|" . $periodical . $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 3, STR_PAD_LEFT, '0') . "|") . ';';

			//echo $cookie . "\n";

			$url = $base_url . '?scheme=RefWorks';

			$html = get($url, '', '', array("Cookie: " . $cookie));

			//echo $html;

			$html = preg_replace('/<br\s+\/>/Uu', "\n", $html);

			$lines = explode("\n", $html);

			//print_r($lines);

			$guid = '';

			$keys = array();
			$values = array();

			$multilingual_keys = array();
			$multilingual_values = array();


			$go = false;
			foreach ($lines as $line)
			{
				if (preg_match('/<div id="export_container">/', $line))
				{
					$go = true;
				}

				if ($go && preg_match('/<\/div>/', $line))
				{
					$go = false;
				}
	
				if ($go)
				{
					//echo $line . "\n";
		
					if (preg_match('/^(?<key>[A-Z][A-Z0-9])\s+(?<value>.*)$/Uu', $line, $m))
					{
						//print_r($m);
						$k 	= $m['key'];
						$v 	= $m['value'];
	
						$v = preg_replace('/<br \/>$/u', '', $v);
						$v = preg_replace('/\s+\[doi\]/u', '', $v);
			
						$key = '';
			
						switch ($k)
						{
							// English language title
							case 'T2':
								$kk = array();
								$vv = array();
								$kk[] = "`key`";
								$vv[] = '"title"';

								$kk[] = 'language';
								$vv[] = '"en"';
								
								$kk[] = 'value';
								$vv[] = '"' . addcslashes($v, '"') . '"';
		
								$multilingual_keys[] = $kk;
								$multilingual_values[] = $vv;							
								break;				
					
							case 'OP':
								$matched = false;
								if (!$matched)
								{
									if (preg_match('/(?<spage>\d+)-(?<epage>\d+)/', $v, $m))
									{
										$keys[] = 'spage';
										$values[] = $m['spage'];
										$keys[] = 'epage';
										$values[] = $m['epage'];
										$matched = true;
									}
								}
								if (!$matched)
								{
									if (preg_match('/(?<spage>\d+)$/', $v, $m))
									{
										$keys[] = 'spage';
										$values[] = $m['spage'];
										$matched = true;
									}
								}
								break;
					
							case 'DO':
								$keys[] = $key_map[$k];
								$values[] = '"' . addcslashes($v, '"') . '"';
								$guid = $v;
								break;

							case 'LK':
								$keys[] = $key_map[$k];
								$values[] = '"' . addcslashes($v, '"') . '"';
								$url = $v;
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
										$vv[] = '"zh"';
											
										$kk[] = 'value';
										$vv[] = '"' . addcslashes($v, '"') . '"';
					
										$multilingual_keys[] = $kk;
										$multilingual_values[] = $vv;
									}
									else
									{
										switch ($key)
										{
											case 'journal':
												$kk = array();
												$vv = array();
												$kk[] = "`key`";
												$vv[] = '"' . $key . '"';

												$kk[] = 'language';
												$vv[] = '"en"';
											
												$kk[] = 'value';
												$vv[] = '"' . addcslashes($v, '"') . '"';
					
												$multilingual_keys[] = $kk;
												$multilingual_values[] = $vv;											
												break;
												
											default:
												break;
										}
									
									
									}
						
								}
								break;
						}
			
						if ($key != '')
						{
							if (!in_array($key, $have))
							{
								$keys[] = $key;
								$values[] = '"' . addcslashes($v, '"') . '"';
								
								$have[] = $key;
							}
						}
					}
				}
			}

			if (count($keys) > 0)
			{
				//print_r($keys);
				//print_r($values);

				//print_r($multilingual_keys);
				//print_r($multilingual_values);
		
				if ($guid == '')
				{
					$guid = $url;
				}
				
		
				if ($guid == '')
				{	
					$guid = md5(join('', $values));
				}
				$keys[] = 'guid';
				$values[] = '"' . $guid . '"';


				// populate from scratch
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
			}
		}
		$rand = rand(1000000, 3000000);
		echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
	}
}		



?>