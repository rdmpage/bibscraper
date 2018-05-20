<?php

require_once (dirname(dirname(__FILE__)) . '/couchsimple.php');

require_once(dirname(__FILE__) . '/lookup.php');

//--------------------------------------------------------------------------------------------------
function get_authors(&$citation, $authorstring)
{
	//echo "$authorstring\n";
	$authorstring = preg_replace('/\s+([A-Z]).,/', ' $1|', $authorstring);
	
	// protect
	$authorstring = preg_replace('/von (\w+)/', 'Von-$1', $authorstring);
	
	$authorstring = preg_replace('/\s+([A-Z]\w+(-\w+)?),/', ' $1|', $authorstring);

	// unprotect
	$authorstring = preg_replace('/Von-(\w+)/', 'von $1', $authorstring);
	
	
	//echo "$authorstring\n";
	$authorstring = preg_replace('/&amp;/', '|', $authorstring);
	//echo "$authorstring\n";
	$authorstring = preg_replace("/\|\s*\|/Uu", "|", $authorstring);				
	//echo "$authorstring\n";
	$authorstring = preg_replace("/\|\s*/Uu", "|", $authorstring);
	$authorstring = preg_replace('/\s+$/Uu', "", $authorstring);
	$authorstring = preg_replace('/\|$/Uu', "", $authorstring);

	//echo "$authorstring\n";
	$authors = explode("|", $authorstring);
	
	for ($i = 0; $i < count($authors); $i++)
	{
		$authors[$i] = preg_replace('/\.([A-Z])/u', ". $1", $authors[$i]);
		$authors[$i] = preg_replace('/^\s+/u', "", $authors[$i]);
		$authors[$i] = preg_replace('/\,$/u', "", $authors[$i]);
		
		$author = new stdclass;
		
		if (preg_match('/^(?<lastname>\w+(-\w+)?),\s*(?<firstname>.*)$/', $authors[$i], $mm))
		{
			$author->firstname = str_replace('.', '', $mm['firstname']);
			$author->lastname = $mm['lastname'];
			$author->name = $author->firstname . ' ' . $author->lastname;
		}
		else
		{
			if (preg_match('/^(?<firstname>[A-Z]\.\s+)+(?<lastname>\w+(-\w+)?)$/', $authors[$i], $mm))
			{
				$author->firstname = str_replace('.', '', $mm['firstname']);
				$author->lastname = $mm['lastname'];
				$author->name = $author->firstname . ' ' . $author->lastname;
			}				
		}
		if (!isset($author->name))
		{
			$author->name = $authors[$i];
		}
		
		$citation->author[] = $author;
		
		
	}
}


//--------------------------------------------------------------------------------------------------
// extract list of references from HTML and return as BibJSON array
function get_references_from_html($doi)
{
	$url = 'http://dx.doi.org/' . $doi;

	$html = get($url);
	
	$html = str_replace("\r", " ", $html);
	$html = str_replace("\n", " ", $html);
	
	$dois = array();
	

	if (preg_match_all('/<li>\s*<span class="authors">(?<citation>.*)(<\/span>\s*)?<\/li>/Uu', $html, $matches))
	{
		$n = count($matches['citation']);
		
		$citations = array();
		
		for ($i = 0; $i < $n; $i++)
		{
			$matched = false;
			
			$citation = new stdclass;
			$citation->id = $i;
			$citation->html = $matches['citation'][$i];
			$citation->type = 'generic';
			
			
			if (!$matched)
			{
				if (preg_match('/(?<authorstring>.*)\((?<year>[0-9]{4})[a-z]?\)\s*(?<title>.*)[\.|\?]\s*<em class="a-plus-plus">(?<journal>.*)<\/em>,(.*,)?\s*<strong class="a-plus-plus">(?<volume>.*)<\/strong>,\s+(?<spage>\d+)–(?<epage>\d+)(, \d+ pl)?\.$/', $citation->html, $m))
				{
					print_r($m);	
	
					$citation->type = 'article';
					$citation->title = strip_tags($m['title']);
					$citation->year = $m['year'];
					
					get_authors($citation, $m['authorstring']);
					
					$citation->journal = new stdclass;
					$citation->journal->name = $m['journal'];
					$citation->journal->volume = $m['volume'];
					$citation->journal->pages = $m['spage'] . '--' . $m['epage'];
					
					$matched = true;
				}
			}
			

			if (!$matched)
			{
				if (preg_match('/(?<authorstring>.*)(?<year>[0-9]{4})[a-z]?\.\s*(?<title>.*)[\.|\?]\s*(?<journal>.*)\s+(?<volume>\d+)(\((?<issue>.*)\))?:\s+(?<spage>\d+)–(?<epage>\d+)(, \d+ pl)?\.(<\/span>\s+<a\s+class="external"\s+href="http:\/\/dx.doi.org\/(?<doi>.*)"\s+target)?/', $citation->html, $m))
				{
					//print_r($m);	
	
					$citation->type = 'article';
					$citation->title = strip_tags($m['title']);
					$citation->year = $m['year'];
					
					get_authors($citation, $m['authorstring']);
					
					$citation->journal = new stdclass;
					$citation->journal->name = $m['journal'];
					$citation->journal->volume = $m['volume'];
					$citation->journal->pages = $m['spage'] . '--' . $m['epage'];
					
					if ($m['doi'] != '')
					{
						$identifier = new stdclass;
						$identifier->type = 'doi';
						$identifier->id = $m['doi'];
						$citation->identifier = array();
						$citation->identifier[] = $identifier;
						
						$dois[] = $m['doi'];
					}
					
					$matched = true;
				}
			}
			
			
			// failed to parse
			if (!$matched)
			{
				if (preg_match('/(?<authorstring>.*)\((?<year>[0-9]{4})[a-z]?\)\s+(?<title>.*)$/', $citation->html, $m))
				{
					print_r($m);	
	
					$citation->title = strip_tags($m['title']);
					$citation->year = $m['year'];
					
					get_authors($citation, $m['authorstring']);
					
					
					$matched = true;
				}
			}
			
			
			print_r($citation);
			
			$citations[] = $citation;
	
	
		}
	}
	
	
	print_r($citations);
	
	return $citations;
	
	
}

//--------------------------------------------------------------------------------------------------
// Look up citations in BioNames
function match_citations($citations)
{
	$references = array();
	
	
	foreach ($citations as $citation)
	{
		if (1)
		{
			echo '.';
			$references[] = lookup($citation);
		}
		else
		{
			$references[] = $citation;
		}
	}
	
	return $references;
}


//--------------------------------------------------------------------------------------------------
$force = true;

$sicis=array('70345580b13d4ba712e9da364d7b5bc4'); 
//$sicis=array('8bbefb28a3b504e413472b5b343cee58');

// different reference style (sigh)
$sicis=array('532ce7992af435ef87e6172776c21d5b');

$sicis=array('994ef58be03c7bcbd8351136ad3d80b8');

$sicis=array('10.1007/BF00009979');

$sicis=array('10.1007/s10750-013-1643-1');

foreach ($sicis as $sici)
{
	echo "$sici\n";
	
	// Get reference from CouchDB	
	$url = 'http://bionames.org/bionames-api/api_id.php?id=' . urlencode($sici);
		
	$json = get($url);
	
	if ($json != '')
	{
		$reference = json_decode($json);
		
		print_r($reference);
			
		
		if (isset($reference->references) && !$force)
		{
			// skip
			echo "...done!\n";
		}
		else
		{
			// either no references, or we are forcing an update
			
			$doi = '';
			
			foreach ($reference->identifier as $identifier)
			{
				if ($identifier->type == 'doi')
				{
					$doi = $identifier->id;
				}
			}
			
			if ($doi != '')
			{
				$citations = get_references_from_html($doi);
				
				$reference->references = match_citations($citations);
				
				print_r($dois);
				
					
				$resp = $couch->send("PUT", "/" . $config['couchdb_options']['database'] . "/" . urlencode($sici), json_encode($reference));
				var_dump($resp);
			}
		}
		
	}
}



?>