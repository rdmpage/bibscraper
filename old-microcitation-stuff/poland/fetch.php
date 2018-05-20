<?php

require_once('../lib.php');

// articles
$ids = array();

//for ($year = 2001; $year < 2013; $year++)
for ($year = 2001; $year < 2006; $year++)
{
	$url = 'http://bomax.botany.pl/pubs/data/articles?periodical=3&annual=' . $year;
	
	$json = get($url);
	
	$obj = json_decode($json);
	
	//print_r($obj);
	
	foreach ($obj->articles as $article)
	{
		$ids[] = $article->id;
	}
	
	
}

foreach ($ids as $id)
{
	$keys = array();
	$values = array();

	$url = 'http://bomax.botany.pl/pubs/data/article?id=' . $id;
	
	$json = get($url);
	
	//echo $json;
	
	$obj = json_decode($json);

	//print_r($obj);
	
	/*
	  [id] => 742
    [title] => EPILITHIC LICHENS ON SERPENTINITE ROCKS IN POLAND
    [abstract] => A list of 84 lichen species occurring on serpentinites in Lower Silesia (southwestern Poland) is presented. The studied lichen flora is relatively poor, due to the mineralogical and chemical properties of the serpentinic substrate, together with the artificial character of most of the exposures (quarries). No exclusive serpentinophytes were found. The lichen flora is composed mainly of species characteristic for neutral or slightly alkaline siliceous rocks, together with basi and calciphilous taxa and some species typical for mineral and metal enriched substrates. One of them, Catillaria atomarioides (Müll. Arg.) Kilias, is recorded for the first time from Poland.
    [keywords] => Lichens, serpentinites, Lower Silesia, distribution, Poland
    [available] => 1
    [mime_type] => application/pdf
    [periodical_id] => 3
    [periodical] => Polish Botanical Journal
    [annual] => 2001
    [total_number] => 46
    [number] => 2
    [pages] => 191–197
    [search_pattern] => ^PBJ Vol.\s+<total_number>,\s+No.\s+<number>(\s+.*)?$
*/

	$key_map = array(
		'title' 		=> 'title',
		'abstract' 		=> 'abstract',
		'annual' 		=> 'year',
		'periodical' 	=> 'journal',
		'total_number' 	=> 'volume',
		'number' 		=> 'issue'
		);
	
	
	
	if (isset($obj->available))
	{
		foreach ($obj as $k => $v)
		{
			switch ($k)
			{
				case 'pages':
					if (preg_match('/(?<spage>\d+)[-|–|–](?<epage>\d+)/', $v, $m))
					{
						$keys[] = 'spage';
						$values[] = '"' . addcslashes($m['spage'], '"') . '"';
						$keys[] = 'epage';
						$values[] = '"' . addcslashes($m['epage'], '"') . '"';					
					}
					
					if (preg_match('/(?<spage>\d+)–(?<epage>\d+)/', $v, $m))
					{
						$keys[] = 'spage';
						$values[] = '"' . addcslashes($m['spage'], '"') . '"';
						$keys[] = 'epage';
						$values[] = '"' . addcslashes($m['epage'], '"') . '"';					
					}
					
					break;
					
				case 'authors':
					$authors = array();
					foreach ($v as $a)
					{
						$authors[] = $a->lastname . ', ' . $a->firstname;
					}
					$keys[] = 'authors';
					$values[] = '"' . addcslashes(join(";", $authors), '"') . '"';
					
					break;
					
				default:
					if (isset($key_map[$k]))
					{
						$keys[] = $key_map[$k];
						$values[] = '"' . addcslashes($v, '"') . '"';
					}
					break;
			}
		}
	}
	
	$url = 'http://bomax.botany.pl/pubs-new/#article-' . $obj->id;
	$pdf = 'http://bomax.botany.pl/pubs/data/article_pdf?id=' . $obj->id;
	
	
	$keys[] = 'issn';
	$values[] = '"1641-8190"';
	
	$keys[] = 'url';
	$values[] = '"' . addcslashes($url, '"') . '"';
	
	$keys[] = 'pdf';
	$values[] = '"' . addcslashes($pdf, '"') . '"';
	
	$guid = $url;
	$keys[] = 'guid';
	$values[] = '"' . addcslashes($guid, '"') . '"';
	
	
	//print_r($keys);
	//print_r($values);
	
	$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
			. join(',', $values) . ');';
	echo $sql . "\n";


	
	
}	




?>