<?php

// Template to fecth URLs

require_once('../../lib.php');


$basedir = 'html';

for ($volume = 19; $volume < 40; $volume++)
{
	for ($issue = 1; $issue <= 4; $issue++)
	{
		$url = 'http://sciencepress.mnhn.fr/en/periodiques/geodiversitas/' . $volume . '/' . $issue;
		
		$html = get($url);
		
		//echo $html;
		
		if ($html != '')
		{
			if (preg_match_all('/<a href="(?<url>\/en\/periodiques\/geodiversitas\/\d+\/\d+\/.*)"/Uu', $html, $m ))
			{
				print_r($m);
				
				$urls = array_unique($m['url']);
				
				print_r($urls);
				
				foreach ($urls as $u)
				{
					
					$filename = str_replace('/en/periodiques/geodiversitas/', '', $u);
					$filename = str_replace('/', '-', $filename);
					
					$filename = $basedir . '/' . $filename;
					
					
					$u = 'http://sciencepress.mnhn.fr' . $u;
					
					$html = get($u);
					file_put_contents($filename, $html);
					
					
					
				
				}
			}
		}
		
		exit();
		
				
		{
			$rand = rand(1000000, 3000000);
			echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
			usleep($rand);
		}
		
		
		exit();
		
		
	}
}

// <a href="/en/periodiques/geodiversitas/25/1/radiolaires-nassellaires-mono-et-dicyrtides-du-tithonien-inferieur-de-la-region-de-solnhofen-allemagne-meridionale" class="btn btn-grey btn-sm">See article</a>

?>