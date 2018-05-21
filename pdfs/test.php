<?php

require_once (dirname(dirname(__FILE__)) . '/lib.php');

function get_pdf_filename($pdf)
{
	$filename = '';
	
	
	//http://bibliotheques.mnhn.fr/EXPLOITATION/infodoc/ged/viewportalpublished.ashx?eid=IFD_FICJOINT_
	if ($filename == '')
	{
		if (preg_match('/http:\/\/bibliotheques.mnhn.fr\/EXPLOITATION\/infodoc\/ged\/viewportalpublished.ashx\?eid=IFD_FICJOINT_(?<id>.*)/', $pdf, $m))
		{
			$filename = $m['id'] . '.pdf';
		}
	}	
	
	
	// article_pdf?id=
	
	// http://maxbot.botany.pl/cgi-bin/pubs/data/article_pdf?id=712
	if ($filename == '')
	{
		if (preg_match('/http:\/\/maxbot.botany.pl\/cgi-bin\/pubs\/data\/article_pdf\?id=(?<id>.*)/', $pdf, $m))
		{
			$filename = $m['id'] . '.pdf';
			$filename = str_replace('/', '-', $filename);
		}
	}	
	
	// http://www.folia.socmexent.org/revista/folia/
	if ($filename == '')
	{
		if (preg_match('/http:\/\/www.folia.socmexent.org/', $pdf, $m))
		{
			$filename = str_replace('http://www.folia.socmexent.org/revista/folia/', '', $pdf);
			$filename = str_replace('/', '-', $filename);
		}
	}	


	if ($filename == '')
	{
		if (preg_match('/vital:(?<id>\d+)\/SOURCEPDF/', $pdf, $m))
		{
			$filename = 'vital' . $m['id'] . '.pdf';
		}
	}	
	
	
//http://vital.seals.ac.za:8080/vital/access/services/Download/vital:15027/SOURCEPDF	
	
	
	// http://www.mus-nh.city.osaka.jp/publication/bulletin/bulletin/1/1-001.pdf
	if ($filename == '')
	{
		if (preg_match('/http:\/\/www.mus-nh.city.osaka.jp\/publication\/bulletin\/bulletin\/(?<id>.*)\.pdf/', $pdf, $m))
		{
			$filename = $m['id'] . '.pdf';
			$filename = str_replace('/', '-', $filename);
		}
	}	
	
	// http://www.cernuelle.com/dwnld.php?lng=fr&delay=0&pg=356
	if ($filename == '')
	{
		if (preg_match('/dwnld.php\?lng=fr&delay=0&pg=(?<id>\d+)/', $pdf, $m))
		{
			//$pos = strrpos($pdf, '/');
			//$filename = substr($pdf, $pos + 1);
			$filename = $m['id'] . '.pdf';
			$filename = str_replace('/', '.', $filename);
		}
	}	
	
	
	//article/download/asbp.1927.015/6894
	
	if ($filename == '')
	{
		if (preg_match('/\/article\/download\/asbp\.(?<id>\d+\.\d+\/\d+)/', $pdf, $m))
		{
			//$pos = strrpos($pdf, '/');
			//$filename = substr($pdf, $pos + 1);
			$filename = $m['id'] . '.pdf';
			$filename = str_replace('/', '.', $filename);
		}
	}	
	
	
	if ($filename == '')
	{
		if (preg_match('/\/archive\/issn\/0035-9211\/(?<volume>\d+)\/(?<spage>\d+).pdf/', $pdf, $m))
		{
			//$pos = strrpos($pdf, '/');
			//$filename = substr($pdf, $pos + 1);
			$filename = $m['volume'] . '-' . $m['spage'] . '.pdf';
		}
	}	
	
	// http://bbr.nefu.edu.cn/CN/article/downloadArticleFile.do?attachType=PDF&id=1052
	if ($filename == '')
	{
		if (preg_match('/downloadArticleFile.do\?attachType=PDF&id=(?<id>\d+)/', $pdf, $m))
		{
			//$pos = strrpos($pdf, '/');
			//$filename = substr($pdf, $pos + 1);
			$filename = $m['id'] . '.pdf';
		}
	}	
	
	if ($filename == '')
	{
		if (preg_match('/http:\/\/www.e-periodica.ch\/cntmng\?pid=(?<id>seg-(.*))/', $pdf, $m))
		{
			//$pos = strrpos($pdf, '/');
			//$filename = substr($pdf, $pos + 1);
			$filename = $m['id'];
			$filename = str_replace(':', '-', $filename);
		}
	}	
	
	if ($filename == '')
	{
		if (preg_match('/http:\/\/lkcnhm.nus.edu.sg/', $pdf))
		{
			//$pos = strrpos($pdf, '/');
			//$filename = substr($pdf, $pos + 1);
			$filename = basename($pdf);
		}
	}
	
	
	if ($filename == '')
	{
		if (preg_match('/file_no=(?<id>\d+([A-Z]\d+)?)\&/', $pdf, $m))
		{
			$filename = $m['id'];
		}
	}
	
	
	if ($filename == '')
	{
		if (preg_match('/(download|view|downloadSuppFile)\/(?<id1>\d+)\/(?<id2>\d+)$/', $pdf, $m))
		{
			$filename = $m['id1'] . '-' . $m['id2'];
		}
	}
	
	// http://www.scielo.br/pdf/abb/v15n1/5161.pdf'
	if ($filename == '')
	{
		if (preg_match('/pdf\/(.*)\/(.*)\/(.*).pdf$/', $pdf, $m))
		{
			//print_r($m); exit();
			$filename = $m[1] . '-' . $m[2] . '-' . $m[3] . '.pdf';
		}
	}
	
	// http://www1.montpellier.inra.fr/CBGP/acarologia/export_pdf.php?id=4089&typefile=pdf
	if ($filename == '')
	{
		if (preg_match('/\?id=(\d+.*)&/', $pdf, $m))
		{
			//print_r($m); exit();
			$filename = 'acarologia-' . $m[1] . '.pdf';
		}
	}
	
	if ($filename == '')
	{
		if (preg_match('/\?sequence=\d+/', $pdf))
		{
			//$pos = strrpos($pdf, '/');
			//$filename = substr($pdf, $pos + 1);
			$filename = basename($pdf);
			$filename = preg_replace('/\?sequence=\d+/', '', $filename);
		}
	}	
		
	// if no name use basename
	if ($filename == '')
	{
		$filename = basename($pdf);
		$filename = str_replace('lognavi?name=nels&lang=en&type=pdf&id=', '', $filename);
	}
	

	if (!preg_match('/\.pdf$/', $filename))
	{
		$filename .= '.pdf';
	}
	
	$filename = str_replace('getpdf.php?aid=', '', $filename);
	
	
	echo "filename=$filename\n";
	
	return $filename;
}


$filename = dirname(__FILE__) . '/pdfs.txt';

$file_handle = fopen($filename, "r");

$pdfs = array();

$sha1s = array();

$sqls = array();

$count = 1;

while (!feof($file_handle)) 
{
	$pdf = trim(fgets($file_handle));
	
	if (preg_match('/^#/', $pdf))
	{
		// skip
	}
	else
	{
		echo $pdf . "\n";
		
		if (0)
		{
			// just add them all
			$pdfs[]  = $pdf;	
		}
		else
		{
	
			$url = 'http://bionames.org/bionames-archive/havepdf.php?url=' . urlencode($pdf) . '&noredirect=1&format=json';
			//$url = 'http://bionames.org/bionames-archive/havepdf.php?url=' . $pdf . '&noredirect=1&format=json';
		
			//$url = 'http://bionames.org/bionames-archive/pdfstore.php?url=' .  urlencode($pdf) . '&noredirect=1&format=json';

			$json = get($url);
		
			//echo $url . "\n";
		
			//echo $json;
		
			// test if we have	
			$obj = json_decode($json);
			
			//print_r($obj);
		
	
			if ($obj->http_code == 200)
			{		
				echo  "Have: " . $obj->sha1 . "\n";
			
				$sqls[] = 'REPLACE INTO sha1(pdf, sha1) VALUES("' . $pdf . '","' . $obj->sha1 . '");';
			
				$sha1s[] = $obj->sha1;
			}
			else
			{
				echo "Not found $pdf\n";
				$pdfs[]  = $pdf;			
			}
			
			
			if ($count++ % 100 == 0)
			{
				$rand = rand(1000000, 3000000);
				echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
				usleep($rand);
			}

		}
	}	
}

echo "--- list ---\n";
foreach ($pdfs as $pdf)
{
	echo "$pdf\n";
}

echo "--- sha1 ---\n";
file_put_contents(dirname(__FILE__) . '/file_sha.txt', join("\n", $sha1s));

echo "--- curl fetch.sh ---\n";
$curl = "#!/bin/sh\n\n";
foreach ($pdfs as $pdf)
{
	$filename = get_pdf_filename($pdf);
	$curl .= "echo '$filename'\n";
	$curl .= "curl -L '$pdf' > '" . $filename . "'\n";
	$curl .= "sleep 5\n";
}
file_put_contents(dirname(__FILE__) . '/fetch.sh', $curl);

echo "--- extra.txt ---\n";
$extra = '';
foreach ($pdfs as $pdf)
{
	$filename = get_pdf_filename($pdf);
	
	//$filename = str_replace('%20', ' ', $filename);

	//$extra .= "/Users/rpage/Desktop/PDFs/" . $filename . "\t$pdf\n";
	$extra .= "/Users/rpage/Desktop/tmp/" . $filename . "\t$pdf\n";
}
file_put_contents(dirname(__FILE__) . '/extra.txt', $extra);

echo "--- pdf.sql ---\n";
file_put_contents(dirname(__FILE__) . '/pdf.sql', join("\n", $sqls));




?>