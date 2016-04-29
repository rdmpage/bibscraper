<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/utils.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/simplehtmldom_1_5/simple_html_dom.php');


$html = "<p class=MsoNormal style='margin-top:0in;margin-right:2.4in;margin-bottom:0in;
margin-left:.5in;margin-bottom:.0001pt;text-indent:-16.05pt'><span
class=searchnobold1><span style='font-size:12.0pt;font-family:Tahoma;
color:navy'>Nesom, G. L. and T. R. Van Devender.<span
style='mso-spacerun:yes'>  </span>2010.<span style='mso-spacerun:yes'>  </span></span></span><i><span
lang=ES-MX style='font-size:12.0pt;font-family:Tahoma;color:navy;mso-ansi-language:
ES-MX'><a href=\"http://www.phytoneuron.net/PhytoN-Verbmoctezumae.pdf\"><span
style='color:navy'>Verbena moctezumae</span><span style='color:navy;font-style:
normal'> (Verbenaceae), </span><span lang=EN-US style='color:navy;mso-ansi-language:
EN-US;font-style:normal'>a new species from Sonora, Mexico</span></a></span></i><span
style='font-size:12.0pt;font-family:Tahoma;color:navy'>.<span
style='mso-spacerun:yes'>  </span>Phytoneuron 20: 1–5.<b><span
style='mso-spacerun:yes'>   </span></b></span><span style='font-size:10.0pt;
mso-bidi-font-size:12.0pt;font-family:Tahoma'>Mailed </span><st1:date Month=\"6\"
Day=\"21\" Year=\"2010\"><span style='font-size:10.0pt;mso-bidi-font-size:12.0pt;
 font-family:Tahoma'>21 June 2010</span></st1:date><span style='font-size:10.0pt;
mso-bidi-font-size:12.0pt;font-family:Tahoma'>.</span><span style='font-size:
12.0pt;font-family:Tahoma'><span style='mso-spacerun:yes'>  </span><o:p></o:p></span></p>
";

$html = file_get_contents('html/2011.html');



$dom = str_get_html($html);

$ps = $dom->find('p');
foreach ($ps as $p)
{
	$reference = new stdclass;
	
	$spans = $p->find('span[class=searchnobold1]');
	foreach ($spans as $span)
	{
		//echo $span->plaintext . "\n";
		
		if (preg_match('/^(?<authorstring>.*)\s+([0-9]{4})\./', $span->plaintext, $m))
		{
			$authorstring = $m['authorstring'];
			
			$authorstring = preg_replace('/ and /', '|', $authorstring);
			$authorstring = preg_replace('/\.,\s*/', '|', $authorstring);
			
			$reference->authors = explode("|", $authorstring);
		}
	}
		
	/*	
	// title
	$spans = $p->find('span[lang=ES-MX]');
	foreach ($spans as $span)
	{
		//echo $span->plaintext . "\n";
		$reference->title = $span->plaintext;
	}
	*/
	
	/*
	$spans = $p->find('span[style=color:navy]');
	foreach ($spans as $span)
	{
		//echo $span->plaintext . "\n";
		$reference->title = $span->plaintext;
	}
	*/

	
	$spans = $p->find('span');
	foreach ($spans as $span)
	{
		//echo $span->getAttribute("style") . "\n";
		
		if (preg_match('/color:navy/u', $span->getAttribute("style")))
		{
			//echo "|" . $span->plaintext . "\n";
			
			if (preg_match('/(?<authorstring>.*)\s+(?<year>[0-9]{4})\.\s*(?<title>.*)\s+(?<journal>Phytoneuron)\s+(?<volume>\d+(-\d+)?):\s+(?<spage>\d+)(–(?<epage>\d+))?\./Uu', $span->plaintext, $m))
			{
				//print_r($m);
				$authorstring = $m['authorstring'];
			
				$authorstring = preg_replace('/ and /', '|', $authorstring);
				$authorstring = preg_replace('/\.,\s*/', '|', $authorstring);
			
				$reference->authors = explode("|", $authorstring);
				
				$n = count($reference->authors);
				for ($i = 0; $i < $n; $i++)
				{
					$a = $reference->authors[$i];
					$a = preg_replace('/\x{00a0}/u', ' ', $a);
					$a = preg_replace('/,$/u', '', $a);
					$a = preg_replace('/\.\s*$/u', '', $a);
					$a = preg_replace('/’\s/u', '\'', $a);
					$a = preg_replace('/\s\s+/u', ' ', $a);
					
					$reference->authors[$i] = $a;
				}
				
				
				$reference->title = $m['title'];
				$reference->title = preg_replace('/\x{00a0}/u', ' ', $reference->title);
			    $reference->title = preg_replace('/^\s+/u', '', $reference->title);
				$reference->title = preg_replace('/\.\s$/u', '', $reference->title);
				$reference->journal = $m['journal'];
				$reference->issn = '2153-733X';
				$reference->volume = $m['volume']; 
				$reference->spage = $m['spage'];
				if ($m['epage'] != '')
				{ 
					$reference->epage = $m['epage'];
				}
				$reference->year = $m['year']; 
			}
		}
	}
	
	// PDF
	foreach ($p->find('a') as $a)
	{
		//echo $a->href . "\n";
		$reference->pdf = $a->href;
	}
	
	// Date of publication
	$spans = $p->find('st1:date');
	foreach ($spans as $span)
	{
		//echo $span->getAttribute("day") . "\n";
		//echo $span->getAttribute("month") . "\n";
		//echo $span->getAttribute("year") . "\n";
		
		$reference->date = $span->getAttribute("year") . '-' . str_pad($span->getAttribute("month"), 2, '0', STR_PAD_LEFT) . '-' . str_pad($span->getAttribute("day"), 2, '0', STR_PAD_LEFT);
	}	
	
	if (isset($reference->journal))
	{
		//print_r($reference);
	
		echo reference_to_ris($reference);
		
		//echo join("\t", reference_to_tsv($reference)) . "\n";
		
		//echo reference2openurl($reference) . "\n";
	}
	
	
}
	



?>