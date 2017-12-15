<?php

$basedir = dirname(__FILE__) . '/cache/bibliotheques.mnhn.fr/mnhnperiodiques';
//$basedir = dirname(__FILE__) . '/cache/bibliotheques.mnhn.fr/mnhnimages';

$files = scandir($basedir);


//$files=array('1512128906.xml');


foreach ($files as $filename)
{
	if (preg_match('/\d+\.xml$/', $filename))
	{	

		$xml = file_get_contents($basedir . '/' . $filename);

		$xml = str_replace("\n", '', $xml);
		$xml = preg_replace('/<OAI-PMH(.*)>/Uu', '<OAI-PMH>', $xml);

		$dom = new DOMDocument;
		$dom->loadXML($xml);
		$xpath = new DOMXPath($dom);

		$xpath->registerNamespace("dc", "http://purl.org/dc/elements/1.1/");	
		$xpath->registerNamespace("oaidc", "http://www.openarchives.org/OAI/2.0/oai_dc/");	
		$xpath->registerNamespace("xsi", "http://www.w3.org/2001/XMLSchema-instance");	

/*
<record>
<header>
<identifier>oai:bibliotheques.mnhn.fr:AMAMH_S000_2002_T211_N000</identifier>
<datestamp>2017-09-17T22:00:00Z</datestamp>
</header>
<metadata>
<oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<dc:title>Les Amis du Muséum National d'Histoire Naturelle. Vol.211</dc:title>
<dc:type>Fascicule de périodique</dc:type>
<dc:relation>Fait partie de : Les Amis du Muséum national d'histoire naturelle</dc:relation>
<dc:relation>vignette : http://bibliotheques.mnhn.fr/EXPLOITATION/infodoc/digitalCollections/thumb.ashx?seid=AMAMH_S000_2002_T211_N000&amp;i=AMAMH_S000_2002_T211_N000_0001.jpg&amp;s=large#extension.jpg</dc:relation>
<dc:rights>Muséum national d'histoire naturelle (Paris) - Direction des bibliothèques et de la documentation</dc:rights>
<dc:source>Muséum national d'histoire naturelle (Paris) - Direction des bibliothèques et de la documentation, Pr 1926</dc:source>
<dc:date>2002</dc:date>
<dc:date>Septembre 2002</dc:date>
<dc:identifier>AMAMH_S000_2002_T211_N000</dc:identifier>
<dc:identifier>http://bibliotheques.mnhn.fr/medias/doc/EXPLOITATION/IFD/AMAMH_S000_2002_T211_N000/</dc:identifier>
<dc:format>application/pdf ; 21,31 Mo</dc:format>
<dc:format>image/jpeg</dc:format>
<dc:language>fre</dc:language>
</oai_dc:dc>
</metadata>
</record>
*/


		$records = $xpath->query ('//ListRecords/record');
		foreach($records as $record)
		{
			$reference = new stdclass;
	
			$nodeCollection = $xpath->query ('header/identifier', $record);
			foreach($nodeCollection as $node)
			{
				$reference->publisher_id = $node->firstChild->nodeValue;
			}
			
			$nodeCollection = $xpath->query ('metadata', $record);
			foreach($nodeCollection as $node)
			{
	
				$nc = $xpath->query ('oaidc:dc/dc:title', $node);
				foreach($nc as $n)
				{
					$reference->title = $n->firstChild->nodeValue;
				}
				
				
		
				$nc = $xpath->query ('oaidc:dc/dc:publisher', $node);
				foreach($nc as $n)
				{
					$reference->journal = $n->firstChild->nodeValue;
				}

				$nc = $xpath->query ('oaidc:dc/dc:date', $node);
				foreach($nc as $n)
				{
					$reference->year = $n->firstChild->nodeValue;
				}
		
				/*
				$nc = $xpath->query ('oaidc:dc/dc:creator', $node);
				foreach($nc as $n)
				{
					$reference->authors[] = $n->firstChild->nodeValue;
				}
				*/
				
				$nc = $xpath->query ('oaidc:dc/dc:relation', $node);
				foreach($nc as $n)
				{
					$reference->relation[] = $n->firstChild->nodeValue;
				}
				
				
				$nc = $xpath->query ('oaidc:dc/dc:identifier', $node);
				foreach($nc as $n)
				{
					$reference->identifier[] = $n->firstChild->nodeValue;
				}

				$nc = $xpath->query ('oaidc:dc/dc:format', $node);
				foreach($nc as $n)
				{
					$reference->format[] = $n->firstChild->nodeValue;
				}

				$nc = $xpath->query ('oaidc:dc/dc:type', $node);
				foreach($nc as $n)
				{
					$reference->type = $n->firstChild->nodeValue;
				}
				
				
			}

			print_r($reference);

/*	
			$reference->tb2 = $reference->publisher_id;
			$reference->tb2 = str_replace('purl.org/phylo/treebase/phylows/study/TB2:', '', $reference->tb2);
	
			$keys = array('tb2', 'title', 'authors', 'journal', 'year');
			$k = array();
			$v = array();
	
			foreach ($keys as $key)
			{
				if (isset($reference->{$key}))	
				{
					$k[] = $key;
			
					$value = '';
					switch ($key)
					{
						case 'authors':
							$value = join(';', $reference->{$key});
							break;
				
						default:
							$value = $reference->{$key};
							break;
					}
			
					$v[] = '"' . addcslashes($value, '"') . '"';
			
				}
		
		
			}
	
			echo 'REPLACE INTO studies(' . join(',', $k) . ') VALUES (' . join(',', $v) . ');' . "\n";
*/	
		}
	}	
}

?>
