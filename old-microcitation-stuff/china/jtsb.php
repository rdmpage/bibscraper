<?php

// http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200201006&journal_id=jtsb_cn

require_once(dirname(dirname(__FILE__)) . '/lib.php');

require(dirname(dirname(__FILE__)) . '/simplehtmldom_1_5/simple_html_dom.php');



$ids = array();

for($year = 2010; $year < 2011; $year++)
{
	//for($issue = 1; $issue < 7; $issue++)
	for($issue = 1; $issue < 2; $issue++)
	{
		for($article = 1; $article < 20; $article++)
		{
			$ids[] = $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 3, STR_PAD_LEFT, '0');
		}
	}
}

// One record
//$ids=array(); $ids[]='200504002';

//$ids = array(); $ids[] = '199501004';



$count = 0;

foreach ($ids as $id)
{
	$url = 'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=' . $id . '&journal_id=jtsb_cn';
	
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
		
		$pdf = '';

		$keys = array();
		$values = array();

		$multilingual_keys = array();
		$multilingual_values = array();

		$have_authors = false;
		$have_abstract = false;
		
		// Citation string
		$span = $html->find('span[id=ReferenceText]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			// 2002,10(1):33~34
			if (preg_match('/(?<year>[0-9]{4}),(?<volume>\d+)\((?<issue>\d+)\):(?<spage>\d+)(~(?<epage>\d+))?/u', $text, $m))
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
		}
		// Authors Chinese
		$span = $html->find('span[id=ReferenceText]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			// echo $text . "\n";
			
			// 
			if (preg_match('/^(?<authors>(.*)(,.*))\./Uu', $text, $m))
			{
				$authors = $m['authors'];
				$authors = str_replace(',', ';', $authors);
								
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
		}		
		
		// Authors
		$span = $html->find('td[nowrap] a font[color=blue] u');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			//exit();
			
			$text = preg_replace('/，\s+/u', ';' , $text);
			
			$keys[] = 'authors';
			$values[] = '"' . addcslashes($text, '"') . '"';
			

			$language = 'en';

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"authors"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
		
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;			
			
			
		}		
		
				
		
		// Abstract
		$span = $html->find('span[id=EnAbstract]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			$keys[] = 'abstract';
			$values[] = '"' . addcslashes($text, '"') . '"';
			
			$language = 'en';

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"abstract"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
			
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;			
			
		}		
		

		// DOI
		$span = $html->find('span[id=DOI]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			$guid = $text;
			
			$keys[] = 'doi';
			$values[] = '"' . addcslashes($text, '"') . '"';
			
			
		}		

		// PDF
		$span = $html->find('span[id=URL] a');
		foreach ($span as $p)
		{
			$text = $p->href;
			
			//echo $text . "\n";
			
			$text = 'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/' . $text;
			
			$pdf = $text;

			$keys[] = 'pdf';
			$values[] = '"' . addcslashes($text, '"') . '"';
		}		
		
		// journal
		$keys[] = 'journal';
		$values[] = '"Journal of Tropical and Subtropical Botany"';
		
		
		$language = 'en';

		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"journal"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
			
		$kk[] = 'value';
		$vv[] = '"Journal of Tropical and Subtropical Botany"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;			
		
		
		
		// 热带亚热带植物学报
		$language = 'zh';

		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"journal"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
			
		$kk[] = 'value';
		$vv[] = '"热带亚热带植物学报"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;			
		
		
		
		// title (English)
		$span = $html->find('span[id=EnTitle]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			$keys[] = 'title';
			$values[] = '"' . addcslashes($text, '"') . '"';
			
			
			$language = 'en';

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"title"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
				
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;			
		}		

		// title (Chinese)
		$span = $html->find('span[id=FileTitle]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			$language = 'zh';

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"title"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
				
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

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
		
		// Expand DOI for more recent articles if we are adding info
		if (0)
		{
			if (preg_match('/(?<one>10.3969\/j.issn.1005-3395.[0-9]{4}\.)(?<two>\d)(?<three>\.\d+)/', $guid, $m))
			{
				$guid = $m['one'] . '0' . $m['two'] . $m['three'];
			}
		}
			
		$keys[] = 'guid';
		$values[] = '"' . $guid . '"';
		
		$keys[] = 'issn';
		$values[] = '"1005-3395"';


		//print_r($keys);
		//print_r($values);

		//print_r($multilingual_keys);
		//print_r($multilingual_values);
		
		// update
		if (1)
		{		
			if ($guid != '' && $pdf != '')
			{
				$sql = 'UPDATE publication SET pdf="' . $pdf . '" WHERE guid="' . $guid . '";';
				echo $sql . "\n";
			}
		}
		
		// populate from scratch
		if (0) // in_array($reference->journal->volume, array(26,27))) 
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